# Prosemirror JSON Parser

The **prosemirror-json-parser** is a versatile library designed to parse ProseMirror/TipTap JSON content into HTML, featuring custom elements and high extendibility.

It functions seamlessly with both TipTap and ProseMirror, given that TipTap is built upon ProseMirror.

[![PHPUnit Tests](https://github.com/nadar/prosemirror-json-parser/actions/workflows/phpunit.yml/badge.svg)](https://github.com/nadar/prosemirror-json-parser/actions/workflows/phpunit.yml)
[![Maintainability](https://api.codeclimate.com/v1/badges/79f6861128acda33438f/maintainability)](https://codeclimate.com/github/nadar/prosemirror-json-parser/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/79f6861128acda33438f/test_coverage)](https://codeclimate.com/github/nadar/prosemirror-json-parser/test_coverage)
[![Latest Stable Version](https://poser.pugx.org/nadar/prosemirror-json-parser/v/stable)](https://packagist.org/packages/nadar/prosemirror-json-parser)
[![Total Downloads](https://poser.pugx.org/nadar/prosemirror-json-parser/downloads)](https://packagist.org/packages/nadar/prosemirror-json-parser)
[![License](https://poser.pugx.org/nadar/prosemirror-json-parser/license)](https://packagist.org/packages/nadar/prosemirror-json-parser)


![ProseMirror JSON Parser](ai-prosemirror-to-html.jpeg)

**Key Features:**

+ Dependency-free: No additional libraries required for this parser.
+ Exceptional speed: Offers high-performance parsing capabilities.
+ Highly extendible: Enables the addition of custom nodes as per your requirements.

## Installation & Usage

The library can be installed via Composer:

```bash
composer require nadar/prosemirror-json-parser
```

Then add parser to your project and convert the input json code (which must be available as array, so you can use `json_decode($json, true)` to convert the json string into an array) into html:

```php
$html = (new Nadar\ProseMirror\Parser())
    ->toHtml($json);
```

## Extending & Customizing

Each node corresponds to a callable function within the parser, with the node's name serving as the key. This setup facilitates the easy addition or modification of nodes.

For instance, suppose you want to adjust the rendering of the image node. In that case, you can achieve this by incorporating your own function into the parser using the `replaceNode()` method:

```php
$html = (new \Nadar\ProseMirror\Parser());
    ->replaceNode(\Nadar\ProseMirror\Types::image, fn(\Nadar\ProseMirror\Node $node) => '<img src="' . $node->getAttr('src') . '" class="this-is-my-class" />')
    ->toHtml($json);
```

> Check the Types class for all available node types.

Or if you have a custom node with a custom name you can add it to the parser using `addNode` method:

```php
$html = (new \Nadar\ProseMirror\Parser())
    ->addNode('myCustomNode', fn(\Nadar\ProseMirror\Node $node) => '<div class="my-custom-node">...</div>')
    ->toHtml($json);
```

> The `addNode()` and `replaceNode()` methods are internal almost the same, except of that replaceNode should only be used for existing defualt nodes, which are listed in the Types enum class.