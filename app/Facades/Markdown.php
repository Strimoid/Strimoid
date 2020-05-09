<?php

namespace Strimoid\Facades;

use Illuminate\Support\Facades\Facade;

class Markdown extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'markdown';
    }
}
