<?php

declare(strict_types=1);

namespace Kapersoft\Knocker\Commands;

use Closure;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\CallbackEvent;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schedule;
use ReflectionClass;
use ReflectionFunction;
use RuntimeException;

class SendSchedulerTasksCommand extends Command
{
    public $signature = 'knocker:send-scheduler-tasks';

    public $description = 'Send scheduler tasks configuration to Knocker';

    public function handle(): int
    {
        $endpoint = config('knocker-for-laravel.endpoint', '');
        if ($endpoint === '') {
            $this->error('Knocker endpoint is not defined.');

            return Command::FAILURE;
        }

        $token = config('knocker-for-laravel.token', '');
        if ($token === '') {
            $this->error('Knocker auth token is not defined.');

            return Command::FAILURE;
        }

        $url = url('/');
        if ($url === '') {
            $this->error('Application url is not defined.');

            return Command::FAILURE;
        }

        if (! app()->isProduction()) {
            $this->warn('Application is not in production mode, skipping scheduler tasks registration.');

            return Command::SUCCESS;
        }

        $schedulerTasks = $this->schedulerTasks();
        if ($schedulerTasks === []) {
            $this->warn('No scheduler tasks defined.');

            return Command::SUCCESS;
        }

        $response = Http::asJson()
            ->retry(3, throw: false)
            ->withToken($token)
            ->put($endpoint, ['url' => $url, 'scheduler_tasks' => $schedulerTasks]);

        if (! $response->ok() || ! $response->json('success')) {
            $this->error($response->json('message', 'Unknown error while registering scheduler tasks.'));

            return Command::FAILURE;
        }

        $this->line($response->json('message', 'Scheduler task(s) successfully registered'));

        return Command::SUCCESS;
    }

    private function schedulerTasks(): array
    {
        return Collection::make(Schedule::events())
            ->map(fn (Event $event): array => [
                'cron_expression' => $event->expression,
                'command' => $event instanceof CallbackEvent
                    ? $this->getCallbackCommand($event)
                    : $event->normalizeCommand($event->command),
                'description' => $event->description ?: '',
                'timezone' => $event->timezone ?: config('app.timezone'),
            ])
            ->all();
    }

    private function getCallbackCommand(CallbackEvent $event): string
    {
        $callback = (new ReflectionClass($event))->getProperty('callback')->getValue($event);

        if ($callback instanceof Closure) {
            $function = new ReflectionFunction($callback);
            $closureCalledClass = $function->getClosureCalledClass();
            $closureVariables = $function->getClosureUsedVariables();

            if ($closureCalledClass->getName() === \Illuminate\Console\Scheduling\Schedule::class && isset($closureVariables['job'])) {
                return match (true) {
                    is_string($closureVariables['job']) => $closureVariables['job'],
                    is_object($closureVariables['job']) => $closureVariables['job']::class,
                    default => throw new RuntimeException('Invalid job type'),
                };
            }

            return sprintf(
                '%s:%s',
                str_replace($this->laravel->basePath().DIRECTORY_SEPARATOR, '', $function->getFileName() ?: ''),
                $function->getStartLine()
            );
        }

        if (is_string($callback)) {
            return $callback;
        }

        if (is_array($callback)) {
            $className = is_string($callback[0]) ? $callback[0] : $callback[0]::class;

            return sprintf('%s::%s', $className, $callback[1]);
        }

        return sprintf('%s::__invoke', $callback::class);
    }
}
