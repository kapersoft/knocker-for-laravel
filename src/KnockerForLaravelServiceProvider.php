<?php

namespace kapersoft\KnockerForLaravel;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use kapersoft\KnockerForLaravel\Commands\KnockerForLaravelCommand;

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
