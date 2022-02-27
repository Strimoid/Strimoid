<?php

namespace Strimoid\Markdown;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Inline\Parser\InlineParserInterface;
use Strimoid\Markdown\Inline\Element\Spoiler;
use Strimoid\Markdown\Inline\Parser\SpoilerParser;
use Strimoid\Markdown\Inline\Renderer\SpoilerRenderer;

final class SpoilerExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment
            ->addInlineParser(new SpoilerParser())
            ->addRenderer(Spoiler::class, new SpoilerRenderer())
        ;
    }
}
