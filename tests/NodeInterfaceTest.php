<?php

declare(strict_types=1);

namespace Nadar\ProseMirror\Tests;

use Nadar\ProseMirror\Node;
use Nadar\ProseMirror\NodeInterface;
use Nadar\ProseMirror\NodeType;
use Nadar\ProseMirror\Parser;
use PHPUnit\Framework\TestCase;

class Box implements NodeInterface
{
    public function __construct(public readonly string $title, public readonly string $content)
    {
    }

    public static function fromNode(Node $node): static
    {
        return new static($node->getAttr('title'), $node->renderContent());
    }
}

class NodeInterfaceTest extends TestCase
{
    private const JSON = [
        'type' => 'doc',
        'content' => [
            [
                'type' => 'box',
                'attrs' => ['title' => 'Hello'],
                'content' => [
                    ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'World']]],
                ],
            ],
        ],
    ];

    public function testAddNodeWithTypedRenderer()
    {
        $html = (new Parser())
            ->addNode('box', fn (Box $box) => '<div><h3>' . $box->title . '</h3>' . $box->content . '</div>')
            ->toHtml(self::JSON);

        $this->assertSame('<div><h3>Hello</h3><p>World</p></div>', $html);
    }

    public function testReplaceNodeWithTypedRenderer()
    {
        $html = (new Parser())
            ->addNode('box', fn (Node $node) => $node->renderContent())
            ->replaceNode(NodeType::paragraph, fn (Box $box) => '<span>' . $box->content . '</span>')
            ->toHtml(self::JSON);

        $this->assertSame('<span>World</span>', $html);
    }

    public function testRenderersTakingANodeAreUnchanged()
    {
        $html = (new Parser())
            ->addNode('box', fn (Node $node) => get_class($node) . ':' . $node->getAttr('title'))
            ->toHtml(self::JSON);

        $this->assertSame(Node::class . ':Hello', $html);

        $html = (new Parser())
            ->addNode('box', fn ($node) => get_class($node))
            ->toHtml(self::JSON);

        $this->assertSame(Node::class, $html);
    }

    public function testCallableThatIsNoClosure()
    {
        $html = (new Parser())
            ->addNode('box', [$this, 'renderBox'])
            ->toHtml(self::JSON);

        $this->assertSame('Hello', $html);
    }

    public function renderBox(Box $box): string
    {
        return $box->title;
    }
}
