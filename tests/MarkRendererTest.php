<?php

declare(strict_types=1);

namespace Nadar\ProseMirror\Tests;

use Nadar\ProseMirror\Mark;
use Nadar\ProseMirror\Parser;
use PHPUnit\Framework\TestCase;

class MarkRendererTest extends TestCase
{
    public function testDefaultMarkRenderers()
    {
        $json = [
            'type' => 'doc',
            'content' => [
                [
                    'type' => 'paragraph',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => 'bold',
                            'marks' => [['type' => 'bold']]
                        ],
                        [
                            'type' => 'text',
                            'text' => ' '
                        ],
                        [
                            'type' => 'text',
                            'text' => 'italic',
                            'marks' => [['type' => 'italic']]
                        ],
                        [
                            'type' => 'text',
                            'text' => ' '
                        ],
                        [
                            'type' => 'text',
                            'text' => 'link',
                            'marks' => [
                                [
                                    'type' => 'link',
                                    'attrs' => [
                                        'href' => 'https://example.com',
                                        'target' => '_blank'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $parser = new Parser();
        $result = $parser->toHtml($json);
        
        $this->assertSame('<p><strong>bold</strong> <em>italic</em> <a href="https://example.com" target="_blank">link</a></p>', $result);
    }

    public function testReplaceMarkRenderer()
    {
        $json = [
            'type' => 'doc',
            'content' => [
                [
                    'type' => 'paragraph',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => 'Click here',
                            'marks' => [
                                [
                                    'type' => 'link',
                                    'attrs' => [
                                        'href' => 'https://example.com',
                                        'target' => '_blank'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $parser = new Parser();
        $parser->replaceMark('link', function (Mark $mark, string $text) {
            return '<a href="' . $mark->getAttr('href') . '" class="custom-link" rel="noopener">' . $text . '</a>';
        });
        
        $result = $parser->toHtml($json);
        
        $this->assertSame('<p><a href="https://example.com" class="custom-link" rel="noopener">Click here</a></p>', $result);
    }

    public function testAddCustomMarkRenderer()
    {
        $json = [
            'type' => 'doc',
            'content' => [
                [
                    'type' => 'paragraph',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => 'Highlighted text',
                            'marks' => [['type' => 'highlight']]
                        ]
                    ]
                ]
            ]
        ];

        $parser = new Parser();
        $parser->addMark('highlight', fn(Mark $mark, string $text) => '<mark>' . $text . '</mark>');
        
        $result = $parser->toHtml($json);
        
        $this->assertSame('<p><mark>Highlighted text</mark></p>', $result);
    }

    public function testMultipleMarksOnSameText()
    {
        $json = [
            'type' => 'doc',
            'content' => [
                [
                    'type' => 'paragraph',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => 'Bold and Italic',
                            'marks' => [
                                ['type' => 'bold'],
                                ['type' => 'italic']
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $parser = new Parser();
        $result = $parser->toHtml($json);
        
        $this->assertSame('<p><em><strong>Bold and Italic</strong></em></p>', $result);
    }

    public function testUnknownMarkIsIgnored()
    {
        $json = [
            'type' => 'doc',
            'content' => [
                [
                    'type' => 'paragraph',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => 'Text with unknown mark',
                            'marks' => [['type' => 'unknown']]
                        ]
                    ]
                ]
            ]
        ];

        $parser = new Parser();
        $result = $parser->toHtml($json);
        
        // Unknown marks should be ignored (pass-through)
        $this->assertSame('<p>Text with unknown mark</p>', $result);
    }

    public function testReplaceBoldMark()
    {
        $json = [
            'type' => 'doc',
            'content' => [
                [
                    'type' => 'paragraph',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => 'Bold text',
                            'marks' => [['type' => 'bold']]
                        ]
                    ]
                ]
            ]
        ];

        $parser = new Parser();
        $parser->replaceMark('bold', fn(Mark $mark, string $text) => '<b class="font-weight-bold">' . $text . '</b>');
        
        $result = $parser->toHtml($json);
        
        $this->assertSame('<p><b class="font-weight-bold">Bold text</b></p>', $result);
    }

    public function testMarkWithAttributes()
    {
        $json = [
            'type' => 'doc',
            'content' => [
                [
                    'type' => 'paragraph',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => 'Custom mark',
                            'marks' => [
                                [
                                    'type' => 'custom',
                                    'attrs' => [
                                        'color' => 'red',
                                        'size' => 'large'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $parser = new Parser();
        $parser->addMark('custom', function (Mark $mark, string $text) {
            return '<span style="color: ' . $mark->getAttr('color') . '; font-size: ' . $mark->getAttr('size') . ';">' . $text . '</span>';
        });
        
        $result = $parser->toHtml($json);
        
        $this->assertSame('<p><span style="color: red; font-size: large;">Custom mark</span></p>', $result);
    }
}
