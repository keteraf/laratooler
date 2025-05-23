<?php

declare(strict_types=1);

namespace Laratooler\Commands;

use Illuminate\Console\Command;

final class LaratoolerCommand extends Command
{
    public $signature = 'laratooler';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
