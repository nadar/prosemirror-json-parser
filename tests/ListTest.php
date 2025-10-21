<?php

declare(strict_types=1);

namespace Nadar\ProseMirror\Tests;

use Nadar\ProseMirror\Parser;
use PHPUnit\Framework\TestCase;

class ListTest extends TestCase
{
    public function testBulletListWithDefaultBehavior()
    {
        $json = [
            "type" => "doc",
            "content" => [
                [
                    "type" => "bulletList",
                    "content" => [
                        [
                            "type" => "listItem",
                            "content" => [
                                [
                                    "type" => "paragraph",
                                    "content" => [
                                        [
                                            "type" => "text",
                                            "text" => "huhu"
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            "type" => "listItem",
                            "content" => [
                                [
                                    "type" => "paragraph",
                                    "content" => [
                                        [
                                            "type" => "text",
                                            "text" => "hahaha"
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            "type" => "listItem",
                            "content" => [
                                [
                                    "type" => "paragraph",
                                    "content" => [
                                        [
                                            "type" => "text",
                                            "text" => "hihi"
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $parser = new Parser();
        $html = $parser->toHtml($json);
        
        $this->assertSame('<ul><li><p>huhu</p></li><li><p>hahaha</p></li><li><p>hihi</p></li></ul>', $html);
    }

    public function testBulletListWithSkipParagraphsOption()
    {
        $json = [
            "type" => "doc",
            "content" => [
                [
                    "type" => "bulletList",
                    "content" => [
                        [
                            "type" => "listItem",
                            "content" => [
                                [
                                    "type" => "paragraph",
                                    "content" => [
                                        [
                                            "type" => "text",
                                            "text" => "huhu"
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            "type" => "listItem",
                            "content" => [
                                [
                                    "type" => "paragraph",
                                    "content" => [
                                        [
                                            "type" => "text",
                                            "text" => "hahaha"
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            "type" => "listItem",
                            "content" => [
                                [
                                    "type" => "paragraph",
                                    "content" => [
                                        [
                                            "type" => "text",
                                            "text" => "hihi"
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $parser = new Parser();
        $parser->setSkipParagraphsInListItems(true);
        $html = $parser->toHtml($json);
        
        $this->assertSame('<ul><li>huhu</li><li>hahaha</li><li>hihi</li></ul>', $html);
    }

    public function testOrderedListWithSkipParagraphsOption()
    {
        $json = [
            "type" => "doc",
            "content" => [
                [
                    "type" => "orderedList",
                    "content" => [
                        [
                            "type" => "listItem",
                            "content" => [
                                [
                                    "type" => "paragraph",
                                    "content" => [
                                        [
                                            "type" => "text",
                                            "text" => "First item"
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            "type" => "listItem",
                            "content" => [
                                [
                                    "type" => "paragraph",
                                    "content" => [
                                        [
                                            "type" => "text",
                                            "text" => "Second item"
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $parser = new Parser();
        $parser->setSkipParagraphsInListItems(true);
        $html = $parser->toHtml($json);
        
        $this->assertSame('<ol><li>First item</li><li>Second item</li></ol>', $html);
    }

    public function testParagraphsOutsideListsNotAffected()
    {
        $json = [
            "type" => "doc",
            "content" => [
                [
                    "type" => "paragraph",
                    "content" => [
                        [
                            "type" => "text",
                            "text" => "Normal paragraph"
                        ]
                    ]
                ],
                [
                    "type" => "bulletList",
                    "content" => [
                        [
                            "type" => "listItem",
                            "content" => [
                                [
                                    "type" => "paragraph",
                                    "content" => [
                                        [
                                            "type" => "text",
                                            "text" => "List item"
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                [
                    "type" => "paragraph",
                    "content" => [
                        [
                            "type" => "text",
                            "text" => "Another paragraph"
                        ]
                    ]
                ]
            ]
        ];

        $parser = new Parser();
        $parser->setSkipParagraphsInListItems(true);
        $html = $parser->toHtml($json);
        
        $this->assertSame('<p>Normal paragraph</p><ul><li>List item</li></ul><p>Another paragraph</p>', $html);
    }

    public function testNestedListsWithSkipParagraphsOption()
    {
        $json = [
            "type" => "doc",
            "content" => [
                [
                    "type" => "bulletList",
                    "content" => [
                        [
                            "type" => "listItem",
                            "content" => [
                                [
                                    "type" => "paragraph",
                                    "content" => [
                                        [
                                            "type" => "text",
                                            "text" => "Parent item"
                                        ]
                                    ]
                                ],
                                [
                                    "type" => "bulletList",
                                    "content" => [
                                        [
                                            "type" => "listItem",
                                            "content" => [
                                                [
                                                    "type" => "paragraph",
                                                    "content" => [
                                                        [
                                                            "type" => "text",
                                                            "text" => "Nested item"
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $parser = new Parser();
        $parser->setSkipParagraphsInListItems(true);
        $html = $parser->toHtml($json);
        
        $this->assertSame('<ul><li>Parent item<ul><li>Nested item</li></ul></li></ul>', $html);
    }

    public function testListItemWithMultipleParagraphs()
    {
        $json = [
            "type" => "doc",
            "content" => [
                [
                    "type" => "bulletList",
                    "content" => [
                        [
                            "type" => "listItem",
                            "content" => [
                                [
                                    "type" => "paragraph",
                                    "content" => [
                                        [
                                            "type" => "text",
                                            "text" => "First paragraph"
                                        ]
                                    ]
                                ],
                                [
                                    "type" => "paragraph",
                                    "content" => [
                                        [
                                            "type" => "text",
                                            "text" => "Second paragraph"
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $parser = new Parser();
        $parser->setSkipParagraphsInListItems(true);
        $html = $parser->toHtml($json);
        
        // When skipParagraphsInListItems is true, paragraphs are rendered without <p> tags
        $this->assertSame('<ul><li>First paragraphSecond paragraph</li></ul>', $html);
    }

    public function testGetterForSkipParagraphsInListItems()
    {
        $parser = new Parser();
        
        $this->assertFalse($parser->getSkipParagraphsInListItems());
        
        $parser->setSkipParagraphsInListItems(true);
        $this->assertTrue($parser->getSkipParagraphsInListItems());
        
        $parser->setSkipParagraphsInListItems(false);
        $this->assertFalse($parser->getSkipParagraphsInListItems());
    }
}
