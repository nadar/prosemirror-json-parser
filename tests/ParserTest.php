<?php

declare(strict_types=1);

namespace Nadar\ProseMirror\Tests;

use Nadar\ProseMirror\Node;
use Nadar\ProseMirror\NodeType;
use Nadar\ProseMirror\Parser;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    public function testCompile()
    {
        $path = __DIR__ . '/example1.json';
        $buff = file_get_contents($path);
        $json = json_decode($buff, true);

        $wysiwyg = new Parser();
        $result = $wysiwyg->toHtml($json);
        $html = '<p>Das ist ein Test.</p><div>accordion does not exists. </div><p></p><div>accordion does not exists. </div><p>Before hard break<br />After hard break</p><p>Lorem ipsum dolor sit amet, consetetur sadipscing elit.</p><p></p><p></p><p>Some empty p&apos;s above</p><p>Test</p><blockquote><p>some quotes</p></blockquote><img src="https://storage.flyo.cloud/zusammenstellungv03_32528f65.gif/thumb/1400xnull" alt="" title="" /><p></p><p>Wohing geht <a href="mailto:foobar@example.com" target="">das</a>?</p><p>Und <a href="https://luya.io" target="">extern</a>?</p><p>Und <a href="https://luya.io" target="_blank">Targets</a>?</p><p></p><iframe width="560" height="315" src="https://www.youtube.com/watch?v=Ceo8E40vdiI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
        $this->assertSame($html, $result);
    }

    public function testHardBreak()
    {
        $path = __DIR__ . '/hardbreak.json';
        $buff = file_get_contents($path);
        $json = json_decode($buff, true);

        $wysiwyg = new Parser();
        $result = $wysiwyg->toHtml($json);
        $html = '<p>This is some text before a hard break.<br />This is some text after the hard break.</p>';
        $this->assertSame($html, $result);
    }

    public function testMarks()
    {
        $path = __DIR__ . '/marks.json';
        $buff = file_get_contents($path);
        $json = json_decode($buff, true);

        $wysiwyg = new Parser();
        $result = $wysiwyg->toHtml($json);
        $html = '<p>This is a sample text with <strong>bold</strong>, <em>italic</em>, <u>underline</u>, and <s>strikethrough</s> formatting.</p>';
        $this->assertSame($html, $result);
    }

    public function testXss()
    {
        $json = <<<EOT
        {
            "type": "doc",
            "content": [
                {
                    "type": "paragraph",
                    "content": [
                        {
                            "type": "text",
                            "text": "<script>alert('xss');</script>"
                        }
                    ]
                }
            ]
        }
        EOT;

        $wysiwyg = new Parser();
        $result = $wysiwyg->toHtml(json_decode($json, true));

        $this->assertSame('<p>&lt;script&gt;alert(&apos;xss&apos;);&lt;/script&gt;</p>', $result);

        // test without xss filter:

        $xss = <<<EOT
                {
                    "type": "text",
                    "text": "<script>alert('xss');</script>"
                }
        EOT;
        $node = new Node($wysiwyg, json_decode($xss, true));
        $xssResult = $node->getText(false);
        $this->assertSame("<script>alert('xss');</script>", $xssResult);
    }

    public function testCustomNodeRenderer()
    {
        $json = <<<EOT
        {
            "type": "doc",
            "content": [
                {
                    "type": "barfoo",
                    "content": [
                        {
                            "type": "text",
                            "text": "Hello World"
                        }
                    ]
                }
            ]
        }
        EOT;

        $wysiwyg = new Parser();
        $wysiwyg->replaceNode(NodeType::paragraph, function (Node $node) {
            return '<p>Custom Paragraph</p>';
        });

        $wysiwyg->addNode('barfoo', fn (Node $node) => '<div>BarFoo: '.$node->renderContent().'</div>');

        $result = $wysiwyg->toHtml(json_decode($json, true));

        $this->assertSame('<div>BarFoo: Hello World</div>', $result);
    }
}
