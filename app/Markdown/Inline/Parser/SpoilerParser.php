<?php

namespace Strimoid\Markdown\Inline\Parser;

use League\CommonMark\Block\Parser\BlockParserInterface;
use League\CommonMark\ContextInterface;
use League\CommonMark\Cursor;
use League\CommonMark\Inline\Element\HtmlInline;
use League\CommonMark\Inline\Element\Link;
use League\CommonMark\Inline\Parser\InlineParserInterface;
use League\CommonMark\InlineParserContext;
use Strimoid\Markdown\Inline\Element\Spoiler;

class SpoilerParser implements InlineParserInterface
{
    public function getCharacters(): array
    {
        return ['!'];
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        $cursor = $inlineContext->getCursor();

        if ($cursor->peek(-1) !== null && $cursor->peek(1) !== ' ') {
            return false;
        }

        $cursor->advance();

        $spoiler = new Spoiler($cursor->getRemainder());
        $inlineContext->getContainer()->appendChild($spoiler);

        $cursor->advanceToEnd();

        return true;
    }
}
