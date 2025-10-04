<?php

namespace Nadar\ProseMirror;

/**
 * Enumeration representing different node types for ProseMirror.
 *
 * @author Basil <git@nadar.io>
 * @since 1.0.0
 */
enum NodeType
{
    case doc; // Represents the document type.
    case default; // Represents the default type.
    case paragraph; // Represents a paragraph.
    case blockquote; // Represents a blockquote.
    case image; // Represents an image type.
    case heading; // Represents a heading type.
    case youtube; // Represents a YouTube embed type.
    case bulletList; // Represents a bullet list type.
    case orderedList; // Represents an ordered list type.
    case listItem; // Represents a list item type.
    case text; // Represents a text type.
    case codeBlock;
    case horizontalRule;
    case tableRow;
    case tableCell;
    case table;
    case hardBreak;
}
