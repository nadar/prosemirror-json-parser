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
        
        // Default behavior: no paragraph wrapping in list items
        $this->assertSame('<ul><li>huhu</li><li>hahaha</li><li>hihi</li></ul>', $html);
    }

    public function testBulletListWithWrapParagraphsOption()
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
        $parser->setWrapParagraphsInListItems(true);
        $html = $parser->toHtml($json);
        
        // With wrapping enabled: paragraphs are wrapped in <p> tags
        $this->assertSame('<ul><li><p>huhu</p></li><li><p>hahaha</p></li><li><p>hihi</p></li></ul>', $html);
    }

    public function testOrderedListWithDefaultBehavior()
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
        $html = $parser->toHtml($json);
        
        // Default behavior: no paragraph wrapping in list items
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
        $html = $parser->toHtml($json);
        
        // Paragraphs outside lists always have <p> tags
        $this->assertSame('<p>Normal paragraph</p><ul><li>List item</li></ul><p>Another paragraph</p>', $html);
    }

    public function testNestedListsWithDefaultBehavior()
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
        $html = $parser->toHtml($json);
        
        // Default behavior: no paragraph wrapping in list items
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
        $html = $parser->toHtml($json);
        
        // Default behavior: paragraphs in list items are rendered without <p> tags
        $this->assertSame('<ul><li>First paragraphSecond paragraph</li></ul>', $html);
    }

    public function testGetterSetterForWrapParagraphsInListItems()
    {
        $parser = new Parser();
        
        // Default is false (no wrapping)
        $this->assertFalse($parser->getWrapParagraphsInListItems());
        
        $parser->setWrapParagraphsInListItems(true);
        $this->assertTrue($parser->getWrapParagraphsInListItems());
        
        $parser->setWrapParagraphsInListItems(false);
        $this->assertFalse($parser->getWrapParagraphsInListItems());
    }
}
