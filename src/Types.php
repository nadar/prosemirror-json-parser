<?php

namespace Nadar\ProseMirror;

/**
 * Enumeration representing different types for ProseMirror.
 *
 * @author Basil <git@nadar.io>
 * @since 1.0.0
 * @deprecated Use NodeType instead. This class will be removed in a future version.
 */
final class Types
{
    public const doc = NodeType::doc;
    public const default = NodeType::default;
    public const paragraph = NodeType::paragraph;
    public const blockquote = NodeType::blockquote;
    public const image = NodeType::image;
    public const heading = NodeType::heading;
    public const youtube = NodeType::youtube;
    public const bulletList = NodeType::bulletList;
    public const orderedList = NodeType::orderedList;
    public const listItem = NodeType::listItem;
    public const text = NodeType::text;
    public const codeBlock = NodeType::codeBlock;
    public const horizontalRule = NodeType::horizontalRule;
    public const tableRow = NodeType::tableRow;
    public const tableCell = NodeType::tableCell;
    public const table = NodeType::table;
    public const hardBreak = NodeType::hardBreak;
}
