<?php

namespace Nadar\ProseMirror;

class Mark
{
    public function __construct(protected array $mark)
    {

    }

    public function getText(): string
    {
        return $this->mark['text'] ?? '';
    }

    public function getType(): string
    {
        return $this->mark['type'];
    }

    public function getAttrs(): array
    {
        return $this->mark['attrs'] ?? [];
    }

    public function getAttr($name, $defaultValue = '')
    {
        return $this->getAttrs()[$name] ?? $defaultValue;
    }
}
