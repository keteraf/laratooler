<?php

declare(strict_types=1);

namespace Laratooler\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Laratooler\LaratoolerServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

final class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Laratooler\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }

    protected function getPackageProviders($app)
    {
        return [
            LaratoolerServiceProvider::class,
        ];
    }
}
