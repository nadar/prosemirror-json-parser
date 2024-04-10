<?php

declare(strict_types=1);

namespace Nadar\ProseMirror\Tests;

use Nadar\ProseMirror\Parser;
use PHPUnit\Framework\TestCase;

class AdditionalsTest extends TestCase
{
    public function testCompile()
    {
        $path = __DIR__ . '/options.json';
        $buff = file_get_contents($path);
        $json = json_decode($buff, true);

        $wysiwyg = new Parser();
        $result = $wysiwyg->toHtml($json);
        $html = '<pre><code>function hello() {
    console.log(&apos;Hello, World!&apos;);
}</code></pre><hr /><table><tr><td>Cell 1</td><td>Cell 2</td></tr><tr><td>Cell 3</td><td>Cell 4</td></tr></table>';
        $this->assertSame($html, $result);
    }

    public function testWithEmptyNullType()
    {
        $json = <<<EOT
        {
            "type": "doc",
            "content": [
                {
                    "type": null,
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
        $result = $wysiwyg->toHtml(json_decode($json, true));

        $this->assertSame('<div> does not exists. Hello World</div>', $result);

        $json = <<<EOT
        {
            "type": "doc",
            "content": [
                {
                    "type": "",
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
        $result = $wysiwyg->toHtml(json_decode($json, true));

        $this->assertSame('<div> does not exists. Hello World</div>', $result);
    }
}
