<?php

namespace Strimoid\Markdown;

use League\CommonMark\Block\Parser\AbstractBlockParser;
use League\CommonMark\ContextInterface;
use League\CommonMark\Cursor;

class SpoilerParser extends AbstractBlockParser
{
    /**
     *
     * @return bool
     */
    public function parse(ContextInterface $context, Cursor $cursor)
    {
        // TODO: Implement parse() method.
    }
}
