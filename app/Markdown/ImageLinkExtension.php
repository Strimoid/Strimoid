<?php

namespace Strimoid\Markdown;

use League\CommonMark\ConfigurableEnvironmentInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\ExtensionInterface;
use Strimoid\Markdown\Processor\ImageLinkProcessor;

class ImageLinkExtension implements ExtensionInterface
{
    public function register(ConfigurableEnvironmentInterface $environment): void
    {
        $environment->addEventListener(DocumentParsedEvent::class, new ImageLinkProcessor());
    }
}
