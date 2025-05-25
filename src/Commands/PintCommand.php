<?php

declare(strict_types=1);

namespace Laratooler\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

final class PintCommand extends Command
{
    public $signature = 'laratooler:pint';

    public $description = 'Install Laravel Pint and publish a configuration file';

    public function handle(): int
    {
        $this->info('Installing Laravel Pint...');

        // Check if Laravel Pint is already installed
        if (! $this->isPintInstalled()) {
            $this->comment('Laravel Pint is not installed. Installing now...');
            $this->installPint();
        } else {
            $this->comment('Laravel Pint is already installed.');
        }

        // Publish the configuration file
        $this->publishConfig();

        $this->addPintScriptsToComposer();

        $this->info('Laravel Pint has been installed and configured successfully!');
        $this->comment('You can now run Laravel Pint using: php artisan pint');

        return self::SUCCESS;
    }

    /**
     * Checks whether Laravel Pint is installed by verifying the existence of the Pint binary.
     */
    private function isPintInstalled(): bool
    {
        // Check if Laravel Pint is in the composer.json file
        return file_exists(base_path('vendor/bin/pint'));
    }

    /**
     * Installs Laravel Pint by executing the necessary Composer command to add it as a development dependency.
     */
    private function installPint(): void
    {
        $this->comment('Running: composer require laravel/pint --dev');
        exec('composer require laravel/pint --dev');
    }

    /**
     * Publishes the configuration file (pint.json) to the application's root directory.
     *
     * If the configuration file already exists, the user will be prompted to confirm
     * whether they want to overwrite it. If the confirmation is declined, the process
     * will be skipped.
     */
    private function publishConfig(): void
    {
        $configPath = base_path('pint.json');

        if (file_exists($configPath)) {
            if (! $this->confirm('The pint.json file already exists. Do you want to overwrite it?')) {
                $this->comment('Skipping configuration file publishing.');

                return;
            }
        }

        File::copy(__DIR__ . '/../../pint.json', $configPath);

        $this->info('Published configuration file: pint.json');
    }

    /**
     * Adds Pint scripts to composer.json if they don't already exist.
     */
    private function addPintScriptsToComposer(): void
    {
        $composerPath = base_path('composer.json');

        if (! file_exists($composerPath)) {
            $this->error('composer.json file not found!');

            return;
        }

        $composerContent = file_get_contents($composerPath);

        if ($composerContent === false) {
            $this->error('Unable to read composer.json file!');

            return;
        }

        $composerJson = json_decode($composerContent, true);

        if (! is_array($composerJson)) {
            $this->error('Invalid JSON in composer.json file!');

            return;
        }

        if (! isset($composerJson['scripts']) || ! is_array($composerJson['scripts'])) {
            $composerJson['scripts'] = [];
        }

        $scriptsUpdated = false;

        // Check and add "lint": "pint" if it doesn't exist
        if (! isset($composerJson['scripts']['lint']) || $composerJson['scripts']['lint'] !== 'pint') {
            $composerJson['scripts']['lint'] = 'pint';
            $scriptsUpdated = true;
            $this->info('Added "lint": "pint" to composer.json scripts.');
        } else {
            $this->comment('"lint": "pint" already exists in composer.json scripts.');
        }

        // Check and add "test:lint": "pint --test" if it doesn't exist
        if (! isset($composerJson['scripts']['test:lint']) || $composerJson['scripts']['test:lint'] !== 'pint --test') {
            $composerJson['scripts']['test:lint'] = 'pint --test';
            $scriptsUpdated = true;
            $this->info('Added "test:lint": "pint --test" to composer.json scripts.');
        } else {
            $this->comment('"test:lint": "pint --test" already exists in composer.json scripts.');
        }

        // Save the updated composer.json if changes were made
        if ($scriptsUpdated) {
            file_put_contents(
                $composerPath,
                json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            );
            $this->info('Updated composer.json with Pint scripts.');
        } else {
            $this->comment('No changes needed for composer.json scripts.');
        }
    }
}
