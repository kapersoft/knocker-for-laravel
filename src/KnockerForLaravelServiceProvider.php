<?php

declare(strict_types=1);

namespace kapersoft\KnockerForLaravel;

use kapersoft\KnockerForLaravel\Commands\KnockerForLaravelCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class KnockerForLaravelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('knocker-for-laravel')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_knocker_for_laravel_table')
            ->hasCommand(KnockerForLaravelCommand::class);
    }
}
