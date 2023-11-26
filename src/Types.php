<?php

namespace Nadar\ProseMirror;

enum Types
{
    case doc;
    case default;
    case paragraph;
    case blockquote;
    case image;
    case heading;
    case youtube;
    case bulletList;
    case orderedList;
    case listItem;
    case text;
}
