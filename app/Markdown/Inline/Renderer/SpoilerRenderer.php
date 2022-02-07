<?php

namespace Strimoid\Markdown\Inline\Renderer;

use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Inline\Renderer\InlineRendererInterface;
use Strimoid\Markdown\Inline\Element\Spoiler;

class SpoilerRenderer implements InlineRendererInterface
{
    public function render(AbstractInline $inline, ElementRendererInterface $htmlRenderer)
    {
        /** @var Spoiler $spoiler */
        $spoiler = $inline;

        $elements = [
            new HtmlElement('a', ['class' => 'show_spoiler'], 'Pokaż ukrytą treść'),
            new HtmlElement('span', ['class' => 'spoiler'], $spoiler->getContent()),
        ];

        return implode('', array_map(fn ($element) => (string) $element, $elements));
    }
}
