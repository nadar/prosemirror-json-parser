# Prosemirror JSON Parser

The **prosemirror-json-parser** is a versatile library designed to parse ProseMirror/TipTap JSON content into HTML, featuring custom elements and high extendibility.

It functions seamlessly with both TipTap and ProseMirror, given that TipTap is built upon ProseMirror.

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
$html = (new Nadar\ProseMirror\Parser())->toHtml($json);
```

## Extending & Customizing

Each node corresponds to a callable function within the parser, with the node's name serving as the key. This setup facilitates the easy addition or modification of nodes.

For instance, suppose you want to adjust the rendering of the image node. In that case, you can achieve this by incorporating your own function into the parser using the `replaceNode()` method:

```php
$parser = new \Nadar\ProseMirror\Parser();
$parser->replaceNode(\Nadar\ProseMirror\Types::image, fn(\Nadar\ProseMirror\Node $node) => '<img src="' . $node->getAttr('src') . '" alt="' . $node->getAttr('alt') . '" class="this-is-my-class" />');
$html = $parser->toHtml($json);
```

> Check the Types class for all available node types.

Or if you have a custom node with a custom name you can add it to the parser using `addNode` method:

```php
$parser = new \Nadar\ProseMirror\Parser();
$parser->addNode('myCustomNode', fn(\Nadar\ProseMirror\Node $node) => '<div class="my-custom-node">...</div>');
$html = $parser->toHtml($json);
```

> The `addNode()` and `replaceNode()` methods are internal almost the same, except of that replaceNode should only be used for existing defualt nodes, which are listed in the Types enum class.