<?php

declare(strict_types=1);

namespace Laratooler\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

final class LarastanCommand extends Command
{
    public $signature = 'laratooler:larastan';

    public $description = 'Install Larastan and publish configuration files';

    public function handle(): int
    {
        $this->info('Installing Larastan...');

        if (! $this->isLarastanInstalled()) {
            $this->comment('Larastan is not installed. Installing now...');
            $this->installLarastan();
        } else {
            $this->comment('Larastan is already installed.');
        }

        $this->publishConfig();

        $this->addLarastanScriptsToComposer();

        $this->info('Larastan has been installed and configured successfully!');
        $this->comment('You can now run Larastan using: ./vendor/bin/phpstan analyse');

        return self::SUCCESS;
    }

    /**
     * Checks whether Larastan is installed by verifying the existence of the PHPStan binary.
     */
    private function isLarastanInstalled(): bool
    {
        return file_exists(base_path('vendor/bin/phpstan'));
    }

    /**
     * Installs Larastan by executing the necessary Composer command to add it as a development dependency.
     */
    private function installLarastan(): void
    {
        $this->comment('Running: composer require nunomaduro/larastan:^2.0 --dev');
        exec('composer require nunomaduro/larastan:^2.0 --dev');
    }

    /**
     * Publishes the configuration files (phpstan.neon and phpstan-baseline.neon) to the application's root directory.
     *
     * If the configuration files already exist, the user will be prompted to confirm
     * whether they want to overwrite them. If the confirmation is declined, the process
     * will be skipped.
     */
    private function publishConfig(): void
    {
        $configPath = base_path('phpstan.neon');
        $baselinePath = base_path('phpstan-baseline.neon');

        if (file_exists($configPath)) {
            if (! $this->confirm('The phpstan.neon file already exists. Do you want to overwrite it?')) {
                $this->comment('Skipping phpstan.neon file publishing.');
            } else {
                File::copy(__DIR__ . '/../../phpstan.neon.dist', $configPath);
                $this->info('Published configuration file: phpstan.neon');
            }
        } else {
            File::copy(__DIR__ . '/../../phpstan.neon.dist', $configPath);
            $this->info('Published configuration file: phpstan.neon');
        }

        if (file_exists($baselinePath)) {
            if (! $this->confirm('The phpstan-baseline.neon file already exists. Do you want to overwrite it?')) {
                $this->comment('Skipping phpstan-baseline.neon file publishing.');
            } else {
                File::copy(__DIR__ . '/../../phpstan-baseline.neon', $baselinePath);
                $this->info('Published configuration file: phpstan-baseline.neon');
            }
        } else {
            File::copy(__DIR__ . '/../../phpstan-baseline.neon', $baselinePath);
            $this->info('Published configuration file: phpstan-baseline.neon');
        }
    }

    /**
     * Adds Larastan scripts to composer.json if they don't already exist.
     */
    private function addLarastanScriptsToComposer(): void
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

        // Check and add "analyse": "phpstan analyse" if it doesn't exist
        if (! isset($composerJson['scripts']['analyse']) || $composerJson['scripts']['analyse'] !== 'phpstan analyse') {
            $composerJson['scripts']['analyse'] = 'phpstan analyse';
            $scriptsUpdated = true;
            $this->info('Added "analyse": "phpstan analyse" to composer.json scripts.');
        } else {
            $this->comment('"analyse": "phpstan analyse" already exists in composer.json scripts.');
        }

        // Save the updated composer.json if changes were made
        if ($scriptsUpdated) {
            file_put_contents(
                $composerPath,
                json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            );
            $this->info('Updated composer.json with Larastan scripts.');
        } else {
            $this->comment('No changes needed for composer.json scripts.');
        }
    }
}
