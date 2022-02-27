<?php

namespace Strimoid\Markdown\Inline\Renderer;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use Strimoid\Markdown\Inline\Element\Spoiler;

class SpoilerRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        /** @var Spoiler $spoiler */
        $spoiler = $node;

        $elements = [
            new HtmlElement('a', ['class' => 'show_spoiler'], 'Pokaż ukrytą treść'),
            new HtmlElement('span', ['class' => 'spoiler'], $spoiler->getLiteral()),
        ];

        return implode('', array_map(fn ($element) => (string) $element, $elements));
    }
}
