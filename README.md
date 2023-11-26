# prosemirror-json-parser

A library to parse prosemirror / TipTap Json into HTML with custom elements and highly extendible

**This works perfect for both TipTap and ProseMirror (as TipTap is based on ProseMirror)**

+ Dependencie free, no other libraries are required for this library
+ Super fast
+ Highly extendible, you can add your own custom nodes.

## Installation & Usage

```
composer require ..
```

```php
$parser = new Parser();
$html = $parser->toHtml($json);
```

## Extend & Customize

Each node is represented by a callable function inside the parser, where the key is the name of the node. So you can easy add your own nodes or overwrite existing nodes.

For example you like to adjust how the image node is rendered, you can do this by adding your own function to the parser:

```php
$parser = new Parser([
    'image' => fn(Node $node) => '<img src="' . $node->getAttr('src') . '" alt="' . $node->getAttr('alt') . '" class="this-is-my-class" />',
]);
$html = $parser->toHtml($json);
```