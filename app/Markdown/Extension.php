<?php

namespace Strimoid\Markdown;

use League\CommonMark\ConfigurableEnvironmentInterface;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Inline\Parser\InlineParserInterface;

final class Extension implements ExtensionInterface
{
    public function register(ConfigurableEnvironmentInterface $environment): void
    {
        $environment
            ->addBlockParser(new SpoilerParser())
            ->addInlineParser(new GroupMentionParser())
            ->addInlineParser(new UserMentionParser())
        ;
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
