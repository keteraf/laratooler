<?php

declare(strict_types=1);

namespace Laratooler;

use Laratooler\Commands\LaratoolerCommand;
use Laratooler\Commands\PintCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class LaratoolerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laratooler')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laratooler_table')
            ->hasCommand(LaratoolerCommand::class)
            ->hasCommand(PintCommand::class);
    }
}
