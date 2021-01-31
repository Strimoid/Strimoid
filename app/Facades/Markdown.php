<?php

namespace Strimoid\Facades;

use Illuminate\Support\Facades\Facade;
use League\CommonMark\CommonMarkConverter;

/**
 * @method static string convertToHtml(string $commonMark)
 */
class Markdown extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'markdown';
    }
}
