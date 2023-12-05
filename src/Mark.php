<?php

namespace Nadar\ProseMirror;

/**
 * Class Mark
 *
 * This class represents a mark in the ProseMirror document tree. Marks are used to annotate content with metadata
 * and can be used to represent things like links, emphasis, etc.
 *
 * @author Basil <git@nadar.io>
 * @since 1.0.0
 */
class Mark
{
    public function __construct(protected array $mark)
    {

    }

    /**
     * Returns the type of the mark.
     *
     * @return string The type of the mark.
     */
    public function getType(): string
    {
        return $this->mark['type'];
    }

    /**
     * Returns the attributes of the mark.
     *
     * @return array The attributes of the mark.
     */
    public function getAttrs(): array
    {
        return $this->mark['attrs'] ?? [];
    }

    /**
     * Returns a specific attribute of the mark.
     *
     * @param string $name The name of the attribute.
     * @param string $defaultValue The default value to return if the attribute does not exist.
     * @return mixed The value of the attribute, or the default value if the attribute does not exist.
     */
    public function getAttr($name, $defaultValue = '')
    {
        return $this->getAttrs()[$name] ?? $defaultValue;
    }
}
