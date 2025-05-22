<?php

declare(strict_types=1);

namespace Laratooler\Facades;

use Illuminate\Support\Facades\Facade;

final class Laratooler extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return self::class;
    }
}
