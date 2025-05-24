<?php

declare(strict_types=1);

namespace Laratooler;

use Laratooler\Commands\LaratoolerCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class LaratoolerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laratooler')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laratooler_table')
            ->hasCommand(LaratoolerCommand::class);
    }
}
