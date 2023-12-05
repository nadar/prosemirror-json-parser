<?php

namespace Nadar\ProseMirror;

/**
 *
 * @author Basil <git@nadar.io>
 * @since 1.0.0
 */
class Parser
{
    /** @var array An array containing node renderers. */
    public array $nodeRenderers = [];

    public function __construct()
    {
        $this->nodeRenderers = $this->getDefaultNodeRenderers();
    }

    /**
    * Retrieves default node renderers.
    *
    * @return array An array of default node renderers.
    */
    public function getDefaultNodeRenderers(): array
    {
        return [
            Types::doc->name => static fn (Node $node) => $node->renderContent(),

            Types::default->name => static fn (Node $node) => '<div>'.$node->getType() . ' does not exists. ' . $node->renderContent().'</div>',

            Types::paragraph->name => static fn (Node $node) => '<p>' . $node->renderContent() . '</p>',

            Types::blockquote->name => static fn (Node $node) => '<blockquote>' . $node->renderContent() . '</blockquote>',

            Types::image->name => static fn (Node $node) => '<img src="' . $node->getAttr('src') . '" alt="' . $node->getAttr('alt') . '" title="' . $node->getAttr('title') . '" />',

            Types::heading->name => static fn (Node $node) => '<h' . $node->getAttr('level') . '>' . $node->renderContent() . '</h' . $node->getAttr('level') . '>',

            Types::youtube->name => static fn (Node $node) => '<iframe width="560" height="315" src="' . $node->getAttr('src') . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>',

            Types::bulletList->name => static fn (Node $node) => '<ul>' . implode('', array_map(static fn ($child) => '<li>' . $node->renderChildNode($child) . '</li>', $node->getContent())) . '</ul>',

            Types::orderedList->name => static fn (Node $node) => '<ol>' . implode('', array_map(static fn ($child) => '<li>' . $node->renderChildNode($child) . '</li>', $node->getContent())) . '</ol>',

            Types::listItem->name => static fn (Node $node) => $node->renderContent(),

            Types::codeBlock->name => static fn (Node $node) => '<pre><code>' . $node->renderContent() . '</code></pre>',

            Types::horizontalRule->name => static fn () => '<hr />',

            Types::table->name => static fn (Node $node) => "<table>{$node->renderContent()}</table>",

            Types::tableRow->name => static fn (Node $node) => "<tr>{$node->renderContent()}</tr>",

            Types::tableCell->name => static fn (Node $node) => "<td>{$node->renderContent()}</td>",

            Types::text->name => static function (Node $node) {
                $text = $node->getText();
                foreach ($node->getMarks() as $mark) {
                    /** @var Mark $mark */
                    if ($mark->getType() === 'bold') {
                        $text = '<strong>' . $text . '</strong>';
                    } elseif ($mark->getType() === "italic") {
                        $text = '<em>' . $text . '</em>';
                    } elseif ($mark->getType() === "underline") {
                        $text = '<u>' . $text . '</u>';
                    } elseif ($mark->getType() === "strike") {
                        $text = '<s>' . $text . '</s>';
                    } elseif ($mark->getType() === "link") {
                        $text = '<a href="' . $mark->getAttr('href') . '" target="' . $mark->getAttr('target') . '">' . $text . '</a>';
                    }
                }
                return $text;
            },
        ];
    }

    /**
    * Replaces a node renderer with a custom renderer.
    *
    * @param Types $type The type of node to replace the renderer for.
    * @param callable $renderer The custom renderer function.
    * @return $this
    */
    public function replaceNode(Types $type, callable $renderer): self
    {
        $this->nodeRenderers[$type->name] = $renderer;
        return $this;
    }

    /**
    * Adds a new node renderer.
    *
    * @param string $type The type of node to add the renderer for.
    * @param callable $renderer The renderer function to add.
    * @return $this
    */
    public function addNode(string $type, callable $renderer): self
    {
        $this->nodeRenderers[$type] = $renderer;
        return $this;
    }

    /**
     * Converts a ProseMirror JSON representation to HTML.
     *
     * @param array $json The ProseMirror JSON representation.
     * @return string The HTML representation of the ProseMirror JSON.
     */
    public function toHtml(array $json): string
    {
        // this will render the first node "document"
        return $this->renderNode($json);
    }

    /**
    * Renders a node from ProseMirror JSON.
    *
    * @param array $json The ProseMirror JSON representation of the node.
    * @return string The rendered HTML of the node.
    */
    public function renderNode(array $json): string
    {
        $node = new Node($this, $json);
        return $node->render();
    }

    /**
     * Finds a node renderer based on the type.
     *
     * @param string $type The type of node to find the renderer for.
     * @return callable The found node renderer.
     */
    public function findNodeRenderer(string $type): callable
    {
        return $this->nodeRenderers[$type] ?? $this->nodeRenderers['default'];
    }
}
