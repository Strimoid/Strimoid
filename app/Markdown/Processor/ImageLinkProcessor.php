<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Strimoid\Markdown\Processor;

use League\CommonMark\EnvironmentInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;

class ImageLinkProcessor
{
    private const IMAGE_EXTENSIONS = ['avif', 'gif', 'jpg', 'jpeg', 'png', 'webp'];

    public function __invoke(DocumentParsedEvent $e): void
    {
        $walker = $e->getDocument()->walker();

        while ($event = $walker->next()) {
            if ($event->isEntering() && $event->getNode() instanceof Link) {
                /** @var Link $link */
                $link = $event->getNode();

                $path = parse_url($link->getUrl(), PHP_URL_PATH);
                $extension = pathinfo($path, PATHINFO_EXTENSION);

                if (in_array($extension, self::IMAGE_EXTENSIONS, false)) {
                    $this->markLinkAsImage($link);
                }
            }
        }
    }

    private function markLinkAsImage(Link $link): void
    {
        $class = $link->data->get('attributes.class', '');
        $link->data->set('attributes.class', trim((string) $class) . ' image');
    }
}
