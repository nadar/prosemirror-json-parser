<?php

namespace Nadar\ProseMirror;

use Nadar\ProseMirror\Nodes\Doc;
use PhpParser\Node\Stmt\Nop;

class Parser
{
   /*
    protected array $defaultNodeRenderers = [
         
        'youtube' => function($attrs) {
          return '<iframe width="560" height="315" src="' . $attrs['src'] . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
        },
        
    ];
    */

    public array $combinedNodeRenderers = [];

    public function getDefaultNodeRenderers() : array
    {
        return [
            Types::doc->name => fn(Node $node) => $node->renderContent(),

            'default' => fn(Node $node) => '<div>'.$node->getType() . ' does not exists. ' . $node->renderContent().'</div>',
            
            'paragraph' => fn(Node $node) => '<p>' . $node->renderContent() . '</p>',

            'blockquote' => fn(Node $node) => '<blockquote>' . $node->renderContent() . '</blockquote>',

            'image' => fn(Node $node) => '<img src="' . $node->getAttr('src') . '" alt="' . $node->getAttr('alt') . '" title="' . $node->getAttr('title') . '" />',
            
            'heading' => fn(Node $node) => '<h' . $node->getAttr('level') . '>' . $node->renderContent() . '</h' . $node->getAttr('level') . '>',
            
            'youtube' => fn(Node $node) => '<iframe width="560" height="315" src="' . $node->getAttr('src') . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>',

            'bulletList' => function(Node $node) {
              return '<ul>' . implode('', array_map(function($child) use ($node) {
                return '<li>' . $node->renderChildNode($child) . '</li>';
              }, $node->getContent())) . '</ul>';
            },
            
            'orderedList' => function(Node $node) {
              return '<ol>' . implode('', array_map(function($child) use ($node) {
                return '<li>' . $node->renderChildNode($child) . '</li>';
              }, $node->getContent())) . '</ol>';
            },
            
            'listItem' => fn(Node $node) => $node->renderContent(),
            
            'text' => function(Node $node) {
                $text = $node->getText();
                foreach ($node->getMarks() as $mark) {
                    if ($mark->getType() === 'bold') {
                        $text = '<strong>' . $text . '</strong>';
                    } else if ($mark->getType() === "italic") {
                        $text = '<em>' . $text . '</em>';
                    } else if ($mark->getType() === "underline") {
                        $text = '<u>' . $text . '</u>';
                    } else if ($mark->getType() === "strikethrough") {
                        $text = '<del>' . $text . '</del>';
                    } else if ($mark->getType() === "link") {
                        $text = '<a href="' . $mark->getAttr('href') . '" target="' . $mark->getAttr('target') . '">' . $text . '</a>';
                    }
                }

                return $text;
            },
        ];
    }

    public function __construct(protected array $nodeRenderers = [])
    {
        $this->combinedNodeRenderers = array_merge($this->getDefaultNodeRenderers(), $this->nodeRenderers);
    }

    public function toHtml(array $json,) : string
    {
        // this will render the first node "document"
        return $this->renderNode($json);
    }

    public function renderNode(array $json) : string
    {
        $node = new Node($this, $json);
        return $node->render();
    }

    public function findNodeRenderer(string $type) : callable
    {
        return $this->combinedNodeRenderers[$type] ?? $this->combinedNodeRenderers['default'];
    }
}