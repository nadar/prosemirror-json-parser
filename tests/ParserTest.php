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
        $x = $wysiwyg->toHtml($json);

        var_dump($x);
    }
}