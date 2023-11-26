<?php declare(strict_types=1);

namespace Nadar\ProseMirror\Tests;

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
        $html = '<p>Das ist ein Test.</p><div>accordion does not exists. </div><p></p><div>accordion does not exists. </div><p>Before hard break<div>hardBreak does not exists. </div>After hard break</p><p>Lorem ipsum dolor sit amet, consetetur sadipscing elit.</p><p></p><p></p><p>Some empty p\'s above</p><p>Test</p><blockquote><p>some quotes</p></blockquote><img src="https://storage.flyo.cloud/zusammenstellungv03_32528f65.gif/thumb/1400xnull" alt="" title="" /><p></p><p>Wohing geht <a href="mailto:foobar@example.com" target="">das</a>?</p><p>Und <a href="https://luya.io" target="">extern</a>?</p><p>Und <a href="https://luya.io" target="_blank">Targets</a>?</p><p></p><iframe width="560" height="315" src="https://www.youtube.com/watch?v=Ceo8E40vdiI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
        $this->assertSame($html, $result);
    }
}