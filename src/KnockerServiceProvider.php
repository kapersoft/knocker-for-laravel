<?php

declare(strict_types=1);

namespace Kapersoft\Knocker;

use Illuminate\Support\ServiceProvider;
use Kapersoft\Knocker\Commands\SendSchedulerTasksCommand;
use Override;

class KnockerServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/knocker-for-laravel.php' => config_path('knocker-for-laravel.php'),
        ], 'knocker-for-laravel-config');

        $this->mergeConfigFrom(
            __DIR__.'/../config/knocker-for-laravel.php', 'knocker-for-laravel'
        );

        if ($this->app->runningInConsole()) {
            $this->commands([
                SendSchedulerTasksCommand::class,
            ]);
        }
    }
}
