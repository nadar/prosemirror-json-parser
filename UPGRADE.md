# Upgrade Guide

## Upgrading from 1.x to 2.x

### BREAKING CHANGE: List Item Paragraph Handling

The default behavior for rendering list items has changed to produce cleaner HTML without paragraph tags.

#### What Changed in 2.x

**Version 1.x behavior:**
List items were rendered with paragraph tags:
```html
<ul><li><p>text</p></li></ul>
```

**Version 2.x behavior (new default):**
List items are rendered without paragraph tags:
```html
<ul><li>text</li></ul>
```

#### How to Upgrade

If you need to restore the version 1.x behavior (paragraph tags in list items), use the `setWrapParagraphsInListItems(true)` method:

```php
$parser = new \Nadar\ProseMirror\Parser();
$parser->setWrapParagraphsInListItems(true);
$html = $parser->toHtml($json);
```

This will produce the previous output format with `<p>` tags inside list items.

#### Rationale

The change was made to:
- Produce cleaner, more semantic HTML by default
- Simplify CSS styling for lists
- Align with common web standards where simple list items don't need paragraph wrapping
- Provide better out-of-the-box defaults for most use cases

Paragraphs outside of list items continue to be rendered with `<p>` tags as expected.
