<?php

namespace Strimoid\Markdown\Inline\Parser;

use League\CommonMark\Parser\Inline\InlineParserInterface;
use League\CommonMark\Parser\Inline\InlineParserMatch;
use League\CommonMark\Parser\InlineParserContext;
use Strimoid\Markdown\Inline\Element\Spoiler;

class SpoilerParser implements InlineParserInterface
{
    public function getMatchDefinition(): InlineParserMatch
    {
        return InlineParserMatch::string('!');
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
