# Prosemirror JSON Parser

The **prosemirror-json-parser** is a versatile library designed to parse ProseMirror/TipTap JSON content into HTML, featuring custom elements and high extendibility.

It functions seamlessly with both TipTap and ProseMirror, given that TipTap is built upon ProseMirror.

**Key Features:**

+ Dependency-free: No additional libraries required for this parser.
+ Exceptional speed: Offers high-performance parsing capabilities.
+ Highly extendible: Enables the addition of custom nodes as per your requirements.

## Installation & Usage

```bash
composer require ...
```

```php
$parser = new Nadar\ProseMirror\Parser();
$html = $parser->toHtml($json);
```

## Extending & Customizing

Each node corresponds to a callable function within the parser, with the node's name serving as the key. This setup facilitates the easy addition or modification of nodes.

For instance, suppose you want to adjust the rendering of the image node. In that case, you can achieve this by incorporating your own function into the parser:

```php
$parser = new \Nadar\ProseMirrorParser([
    'image' => fn(\Nadar\ProseMirror\Node $node) => '<img src="' . $node->getAttr('src') . '" alt="' . $node->getAttr('alt') . '" class="this-is-my-class" />',
]);
$html = $parser->toHtml($json);
```