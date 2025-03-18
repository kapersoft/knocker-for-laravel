<?php

declare(strict_types=1);

namespace kapersoft\KnockerForLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \kapersoft\KnockerForLaravel\KnockerForLaravel
 */
class KnockerForLaravel extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \kapersoft\KnockerForLaravel\KnockerForLaravel::class;
    }
}
