<?php

namespace Strimoid\Facades;

use Illuminate\Support\Facades\Facade;

class PDP extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'pdp';
    }
}
