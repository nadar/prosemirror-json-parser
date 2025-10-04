<?php

namespace Nadar\ProseMirror;

/**
 * Enumeration representing different mark types for ProseMirror.
 *
 * @author Basil <git@nadar.io>
 * @since 1.0.0
 */
enum MarkType
{
    case bold; // Represents bold text.
    case italic; // Represents italic text.
    case underline; // Represents underlined text.
    case strike; // Represents strikethrough text.
    case link; // Represents a hyperlink.
}
