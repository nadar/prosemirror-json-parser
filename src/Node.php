<?php

namespace Nadar\ProseMirror;

use ArrayIterator;

class Node
{
    public function __construct(protected Parser $parser, protected array $node)
    {
        
    }

    public function getType() : string
    {
        return $this->node['type'];
    }

    public function getText() : string
    {
        return $this->node['text'] ?? '';
    }

    /**
     * @return Mark[]
     */
    public function getMarks() : ArrayIterator
    {
        return new ArrayIterator(array_map(fn($item) => new Mark($item), $this->node['marks'] ?? []));
    }

    public function getContent() : array
    {
        return $this->node['content'] ?? [];
    }

    public function getAttrs() : array
    {
        return $this->node['attrs'] ?? [];
    }

    public function getAttr($name, $defaultValue = '')
    {
        return $this->getAttrs()[$name] ?? $defaultValue;
    }

    public function renderContent() : string
    {
        return implode('', array_map(function($child) {
            return $this->renderChildNode($child);
        }, $this->getContent()));
    }

    public function render(): string
    {
        $renderer = $this->parser->findNodeRenderer($this->getType());
        return call_user_func_array($renderer, [$this]);
    }

    public function renderChildNode(array $json) : string
    {
        return $this->parser->renderNode($json);
    }
}