<?php

namespace Nadar\ProseMirror;

class Parser
{
    public array $nodeRenderers = [];

    public function __construct()
    {
        $this->nodeRenderers = $this->getDefaultNodeRenderers();
    }

    public function getDefaultNodeRenderers(): array
    {
        return [
            Types::doc->name => fn (Node $node) => $node->renderContent(),

            Types::default->name => fn (Node $node) => '<div>'.$node->getType() . ' does not exists. ' . $node->renderContent().'</div>',

            Types::paragraph->name => fn (Node $node) => '<p>' . $node->renderContent() . '</p>',

            Types::blockquote->name => fn (Node $node) => '<blockquote>' . $node->renderContent() . '</blockquote>',

            Types::image->name => fn (Node $node) => '<img src="' . $node->getAttr('src') . '" alt="' . $node->getAttr('alt') . '" title="' . $node->getAttr('title') . '" />',

            Types::heading->name => fn (Node $node) => '<h' . $node->getAttr('level') . '>' . $node->renderContent() . '</h' . $node->getAttr('level') . '>',

            Types::youtube->name => fn (Node $node) => '<iframe width="560" height="315" src="' . $node->getAttr('src') . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>',

            Types::bulletList->name => function (Node $node) {
                return '<ul>' . implode('', array_map(function ($child) use ($node) {
                    return '<li>' . $node->renderChildNode($child) . '</li>';
                }, $node->getContent())) . '</ul>';
            },

            Types::orderedList->name => function (Node $node) {
                return '<ol>' . implode('', array_map(function ($child) use ($node) {
                    return '<li>' . $node->renderChildNode($child) . '</li>';
                }, $node->getContent())) . '</ol>';
            },

            Types::listItem->name => fn (Node $node) => $node->renderContent(),

            Types::text->name => function (Node $node) {
                $text = $node->getText();
                foreach ($node->getMarks() as $mark) {
                    if ($mark->getType() === 'bold') {
                        $text = '<strong>' . $text . '</strong>';
                    } elseif ($mark->getType() === "italic") {
                        $text = '<em>' . $text . '</em>';
                    } elseif ($mark->getType() === "underline") {
                        $text = '<u>' . $text . '</u>';
                    } elseif ($mark->getType() === "strikethrough") {
                        $text = '<del>' . $text . '</del>';
                    } elseif ($mark->getType() === "link") {
                        $text = '<a href="' . $mark->getAttr('href') . '" target="' . $mark->getAttr('target') . '">' . $text . '</a>';
                    }
                }

                return $text;
            },
        ];
    }

    public function replaceNode(Types $type, callable $renderer): self
    {
        $this->nodeRenderers[$type->name] = $renderer;
        return $this;
    }

    public function addNode(string $type, callable $renderer): self
    {
        $this->nodeRenderers[$type] = $renderer;
        return $this;
    }

    public function toHtml(array $json): string
    {
        // this will render the first node "document"
        return $this->renderNode($json);
    }

    public function renderNode(array $json): string
    {
        $node = new Node($this, $json);
        return $node->render();
    }

    public function findNodeRenderer(string $type): callable
    {
        return $this->nodeRenderers[$type] ?? $this->nodeRenderers['default'];
    }
}
