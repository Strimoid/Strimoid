<?php

namespace Strimoid\Markdown;

use League\CommonMark\ConfigurableEnvironmentInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Inline\Parser\InlineParserInterface;
use Strimoid\Markdown\Inline\Element\Spoiler;
use Strimoid\Markdown\Inline\Parser\SpoilerParser;
use Strimoid\Markdown\Inline\Renderer\SpoilerRenderer;

final class SpoilerExtension implements ExtensionInterface
{
    public function register(ConfigurableEnvironmentInterface $environment): void
    {
        $environment
            ->addInlineParser(new SpoilerParser())
            ->addInlineRenderer(Spoiler::class, new SpoilerRenderer())
        ;
    }
}
