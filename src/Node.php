<?php

namespace Nadar\ProseMirror;

use ArrayIterator;

/**
 * Class Node
 *
 * This class represents a node in the ProseMirror document tree.
 *
 * @author Basil <git@nadar.io>
 * @since 1.0.0
 */
class Node
{
    public function __construct(protected Parser $parser, protected array $node)
    {

    }

    /**
     * Returns the type of the node.
     *
     * @return string The type of the node.
     */
    public function getType(): string
    {
        return $this->node['type'];
    }

    /**
     * Returns the text of the node.
     *
     * @param bool $sanitize Whether to sanitize the text or not.
     * @return string The text of the node.
     */
    public function getText($sanitize = true): string
    {
        $text =  $this->node['text'] ?? '';

        if (!$sanitize) {
            return $text;
        }

        return htmlspecialchars((string) $text, ENT_QUOTES | ENT_HTML5, 'UTF-8', false);
    }

    /**
     * Returns the marks of the node.
     *
     * @return ArrayIterator<Mark> An array iterator of Mark objects.
     */
    public function getMarks(): ArrayIterator
    {
        return new ArrayIterator(array_map(static fn ($item) => new Mark($item), $this->node['marks'] ?? []));
    }

    /**
     * Returns the content of the node.
     *
     * @return array The content of the node.
     */
    public function getContent(): array
    {
        return $this->node['content'] ?? [];
    }

    /**
     * Returns the attributes of the node.
     *
     * @return array The attributes of the node.
     */
    public function getAttrs(): array
    {
        return $this->node['attrs'] ?? [];
    }

    /**
     * Returns a specific attribute of the node.
     *
     * @param string $name The name of the attribute.
     * @param string $defaultValue The default value to return if the attribute does not exist.
     * @return mixed The value of the attribute, or the default value if the attribute does not exist.
     */
    public function getAttr($name, $defaultValue = '')
    {
        return $this->getAttrs()[$name] ?? $defaultValue;
    }

    /**
     * Renders the content of the node.
     *
     * @return string The rendered content of the node.
     */
    public function renderContent(): string
    {
        return implode('', array_map(fn ($child) => $this->renderChildNode($child), $this->getContent()));
    }

    /**
     * Renders the node.
     *
     * @return string The rendered node.
     */
    public function render(): string
    {
        $renderer = $this->parser->findNodeRenderer($this->getType());
        return call_user_func_array($renderer, [$this]);
    }

    /**
     * Renders a child node.
     *
     * @param array $json The JSON representation of the child node.
     * @return string The rendered child node.
     */
    public function renderChildNode(array $json): string
    {
        return $this->parser->renderNode($json);
    }
}
