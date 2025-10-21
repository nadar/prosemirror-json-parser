# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased]

### Changed - BREAKING CHANGE

**List Item Paragraph Handling**: The default behavior for rendering list items has changed to produce cleaner HTML without paragraph tags.

#### What Changed

Previously, list items were rendered with paragraph tags:
```html
<ul><li><p>text</p></li></ul>
```

Now, list items are rendered without paragraph tags by default:
```html
<ul><li>text</li></ul>
```

#### Migration Guide

If you need to restore the previous behavior (paragraph tags in list items), use the `setWrapParagraphsInListItems(true)` method:

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
