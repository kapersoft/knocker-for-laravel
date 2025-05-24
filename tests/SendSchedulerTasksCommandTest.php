<?php

use Illuminate\Console\Command;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schedule;
use Kapersoft\Knocker\Tests\Fixtures\SendEmails;
use Kapersoft\Knocker\Tests\Fixtures\SendReport;
use Mockery\MockInterface;

beforeEach(function () {
    Config::set('knocker-for-laravel.token', 'test-token');
    Config::set('app.env', 'production');
    $this->mock(UrlGenerator::class, function (MockInterface $mock) {
        $mock->shouldReceive('to')->andReturn('http://example.com');
    });
    app()['env'] = 'production';
});

test('successfully sends scheduler tasks to Knocker', function () {
    // Arrange
    Schedule::call(function () {
        // Do nothing
    })
        ->description('Closure')
        ->lastDayOfMonth()
        ->everyOddHour();
    Schedule::command('about')
        ->description('Command About')
        ->at('08:00')
        ->weekends();
    Schedule::exec('ls -la')
        ->description('Exec ls -la')
        ->everyTenMinutes()
        ->weekdays()
        ->timezone('America/New_York');
    Schedule::call(SendReport::class)
        ->description('Call SendReport')
        ->everyFifteenMinutes()
        ->saturdays();
    Schedule::job(new SendEmails)
        ->description('Job SendEmails')
        ->hourlyAt('35');
    Schedule::job(SendEmails::class)
        ->monthlyOn(1, '06:00')
        ->timezone('Europe/Amsterdam');

    Http::fake([
        'https://knocker.laravel.cloud/api/v1/schedulerTasks' => Http::response([
            'success' => true,
            'message' => 'Success message',
        ], 200),
    ]);

    // Act
    $output = Artisan::call('knocker:send-scheduler-tasks');

    // Assert
    expect($output)->toBe(Command::SUCCESS);

    Http::assertSent(function (Request $request) {
        return $request->url() === 'https://knocker.laravel.cloud/api/v1/schedulerTasks'
            && $request->header('Authorization')[0] === 'Bearer test-token'
            && $request->data() === [
                'url' => 'http://example.com',
                'scheduler_tasks' => [
                    [
                        'cron_expression' => '0 1-23/2 31 * *',
                        'command' => realpath(__DIR__.'/../tests/SendSchedulerTasksCommandTest.php').':25',
                        'description' => 'Closure',
                        'timezone' => 'UTC',
                    ],
                    [
                        'cron_expression' => '0 8 * * 6,0',
                        'command' => 'php artisan about',
                        'description' => 'Command About',
                        'timezone' => 'UTC',
                    ],
                    [
                        'cron_expression' => '*/10 * * * 1-5',
                        'command' => 'ls -la',
                        'description' => 'Exec ls -la',
                        'timezone' => 'America/New_York',
                    ],
                    [
                        'cron_expression' => '*/15 * * * 6',
                        'command' => 'Kapersoft\Knocker\Tests\Fixtures\SendReport',
                        'description' => 'Call SendReport',
                        'timezone' => 'UTC',
                    ],
                    [
                        'cron_expression' => '35 * * * *',
                        'command' => 'Kapersoft\Knocker\Tests\Fixtures\SendEmails',
                        'description' => 'Job SendEmails',
                        'timezone' => 'UTC',
                    ],
                    [
                        'cron_expression' => '0 6 1 * *',
                        'command' => 'Kapersoft\Knocker\Tests\Fixtures\SendEmails',
                        'description' => 'Kapersoft\Knocker\Tests\Fixtures\SendEmails',
                        'timezone' => 'Europe/Amsterdam',
                    ],
                ],
            ];
    });
});

test('fails when endpoint is not configured', function () {
    // Arrange
    Config::set('knocker-for-laravel.endpoint', '');

    // Act
    $output = $this->artisan('knocker:send-scheduler-tasks');

    // Assert
    $output->expectsOutput('Knocker endpoint is not defined.');
    $output->assertExitCode(Command::FAILURE);
});

test('fails when token is not configured', function () {
    // Arrange
    Config::set('knocker-for-laravel.token', '');

    // Act
    $output = $this->artisan('knocker:send-scheduler-tasks');

    // Assert
    $output->expectsOutput('Knocker auth token is not defined.');
    $output->assertExitCode(Command::FAILURE);
});

test('fails when application url is not configured', function () {
    // Arrange
    $this->mock(UrlGenerator::class, function (MockInterface $mock) {
        $mock->shouldReceive('to')->andReturn('');
    });

    // Act
    $output = $this->artisan('knocker:send-scheduler-tasks');

    // Assert
    $output->expectsOutput('Application url is not defined.');
    $output->assertExitCode(Command::FAILURE);
});

test('fails when application is not in production mode', function () {
    // Arrange
    app()['env'] = 'local';

    // Act
    $output = $this->artisan('knocker:send-scheduler-tasks');

    // Assert
    $output->expectsOutput('Application is not in production mode, skipping scheduler tasks registration.');
    $output->assertExitCode(Command::SUCCESS);
});

test('fails when no scheduler tasks are defined', function () {
    // Act
    $output = $this->artisan('knocker:send-scheduler-tasks');

    // Assert
    $output->expectsOutput('No scheduler tasks defined.');
    $output->assertExitCode(Command::SUCCESS);
});

test('fails when HTTP request fails', function () {
    // Arrange
    Schedule::command('about')->description('Command About')->at('08:00')->weekends();

    Http::fake([
        'https://knocker.laravel.cloud/api/v1/schedulerTasks' => Http::response([
            'success' => false,
            'message' => 'An error has occured.',
        ], 500),
    ]);

    // Act
    $output = $this->artisan('knocker:send-scheduler-tasks');

    // Assert
    $output->expectsOutput('An error has occured.');
    $output->assertExitCode(Command::FAILURE);
});
