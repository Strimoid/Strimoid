<?php

namespace Strimoid\Facades;

use Illuminate\Support\Facades\Facade;

class OEmbed extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'oembed';
    }
}
