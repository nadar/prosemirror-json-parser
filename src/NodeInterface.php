<?php

namespace Nadar\ProseMirror;

/**
 * A typed node, which a renderer can take instead of a {@see Node}.
 *
 * When the first parameter of a renderer passed to {@see Parser::addNode()} or
 * {@see Parser::replaceNode()} is typed with a class implementing this interface, the renderer is
 * called with the instance {@see fromNode()} builds, instead of the node itself:
 *
 * ```php
 * class Box implements NodeInterface
 * {
 *     public function __construct(public readonly string $title, public readonly string $content)
 *     {
 *     }
 *
 *     public static function fromNode(Node $node): static
 *     {
 *         return new static($node->getAttr('title'), $node->renderContent());
 *     }
 * }
 *
 * $parser->addNode('box', fn (Box $box) => '<div><h3>' . $box->title . '</h3>' . $box->content . '</div>');
 * ```
 *
 * @author Basil <git@nadar.io>
 * @since 2.2.0
 */
interface NodeInterface
{
    /**
     * Builds the typed node from the node the parser is rendering.
     *
     * @param Node $node The node to build from.
     * @return static
     */
    public static function fromNode(Node $node): static;
}
