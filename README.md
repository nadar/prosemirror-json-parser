# ProseMirror JSON Parser

The ProseMirror JSON Parser (**prosemirror-json-parser**) is a versatile PHP library crafted for effortless conversion of [ProseMirror/TipTap JSON Model](https://prosemirror.net/docs/ref/#model) content into HTML. With dependency-free operation and exceptional parsing speed, this library ensures high-performance HTML generation. Seamlessly integrating with TipTap and ProseMirror, it offers customization through easy addition or modification of nodes. Effortlessly install via Composer, utilize the `toHtml` function and explore extensive customization options for tailored JSON to HTML conversion.

**It functions seamlessly with both TipTap and ProseMirror because TipTap is built upon ProseMirror.**

[![PHPUnit Tests](https://github.com/nadar/prosemirror-json-parser/actions/workflows/phpunit.yml/badge.svg)](https://github.com/nadar/prosemirror-json-parser/actions/workflows/phpunit.yml)
[![Maintainability](https://api.codeclimate.com/v1/badges/79f6861128acda33438f/maintainability)](https://codeclimate.com/github/nadar/prosemirror-json-parser/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/79f6861128acda33438f/test_coverage)](https://codeclimate.com/github/nadar/prosemirror-json-parser/test_coverage)
[![Latest Stable Version](https://poser.pugx.org/nadar/prosemirror-json-parser/v/stable)](https://packagist.org/packages/nadar/prosemirror-json-parser)
[![Total Downloads](https://poser.pugx.org/nadar/prosemirror-json-parser/downloads)](https://packagist.org/packages/nadar/prosemirror-json-parser)
[![License](https://poser.pugx.org/nadar/prosemirror-json-parser/license)](https://packagist.org/packages/nadar/prosemirror-json-parser)


![ProseMirror JSON Parser, what AI thinks about](ai-prosemirror-to-html.webp)

**Key Features:**

+ Dependency-free: No additional libraries required for this parser.
+ Exceptional speed: Offers high-performance parsing capabilities.
+ Highly extendible: Enables the addition of custom nodes as per your requirements.
+ 100% Code Coverage and Testing: Ensures comprehensive test coverage, guaranteeing reliability and stability.
+ Robust out-of-the-box HTML generation: Generates high-quality HTML seamlessly without requiring modifications, ensuring ease of use and reliability.

## Installation & Usage

To install the library using Composer, execute the following command:

```bash
composer require nadar/prosemirror-json-parser
```

After installing the library, integrate the parser into your project. Utilize the `toHtml` function to convert your JSON value into renderable HTML code. Note that the `toHtml` function solely accepts an array. Therefore, if your content is in JSON format, employ `json_decode($json, true)` to initially convert the JSON string into an array and pass it `toHtml(json_decode($json, true))`.

```php
$html = (new Nadar\ProseMirror\Parser())
    ->toHtml($json);
```

## Extending & Customizing

Each node corresponds to a callable function within the parser, using the node's name as the key. This setup allows for easy addition or modification of nodes.

For example, to adjust the rendering of the image node, you can include your own function into the parser using the `replaceNode()` method:

```php
$html = (new \Nadar\ProseMirror\Parser())
    ->replaceNode(\Nadar\ProseMirror\Types::image, fn(\Nadar\ProseMirror\Node $node) => '<img src="' . $node->getAttr('src') . '" class="this-is-my-class" />')
    ->toHtml($json);
```

> To see all default nodes declared, refer to the `Types` class.

If you have a custom node with a specific name, you can add it to the parser using the `addNode()` method:

```php
$html = (new \Nadar\ProseMirror\Parser())
    ->addNode('myCustomNode', fn(\Nadar\ProseMirror\Node $node) => '<div class="my-custom-node">...</div>')
    ->toHtml($json);
```

> The `addNode()` and `replaceNode()` methods are almost identical internally, except that `replaceNode` should only be used when altering the output of default nodes. You can view all by-default declared nodes in the `Types` class.