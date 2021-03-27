<?php

namespace Strimoid\Settings\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed get(string $key)
 */
class Setting extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'settings';
    }
}
