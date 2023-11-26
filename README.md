# prosemirror-json-parser

A library to parse prosemirror / TipTap Json into HTML with custom elements and highly extendible

**This works perfect for both TipTap and ProseMirror (as TipTap is based on ProseMirror)**

+ Dependencie free, no other libraries are required for this library
+ Super fast
+ Highly extendible, you can add your own custom nodes.

## Installation & Usage

````
composer require ..
```

```php
$parser = new Parser();
$html = $parser->toHtml($json);
```

