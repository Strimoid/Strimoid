<?php

namespace Strimoid\Facades;

use Illuminate\Support\Facades\Facade;
use League\CommonMark\Output\RenderedContentInterface;

/**
 * @method static RenderedContentInterface convertToHtml(string $commonMark)
 */
class Markdown extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'markdown';
    }
}
