<?php namespace Strimoid\Markdown;

use League\CommonMark\Block\Parser\BlockParserInterface;
use League\CommonMark\Extension\Extension as BaseExtension;
use League\CommonMark\Inline\Parser\InlineParserInterface;

class Extension extends BaseExtension
{
    /**
     * @return BlockParserInterface[]
     */
    public function getBlockParsers()
    {
        return [
            app(SpoilerParser::class),
        ];
    }

    /**
     * Returns a list of inline parsers to add to the existing list.
     *
     * @return InlineParserInterface[]
     */
    public function getInlineParsers()
    {
        return [
            app(GroupMentionParser::class),
            app(UserMentionParser::class),
        ];
    }
}
