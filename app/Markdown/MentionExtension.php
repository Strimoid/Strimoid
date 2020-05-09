<?php

namespace Strimoid\Markdown;

use League\CommonMark\ConfigurableEnvironmentInterface;
use League\CommonMark\Extension\ExtensionInterface;
use Strimoid\Markdown\Inline\Parser\GroupMentionParser;
use Strimoid\Markdown\Inline\Parser\UserMentionParser;

final class MentionExtension implements ExtensionInterface
{
    public function register(ConfigurableEnvironmentInterface $environment)
    {
        $environment
            ->addInlineParser(new GroupMentionParser())
            ->addInlineParser(new UserMentionParser())
        ;
    }
}
