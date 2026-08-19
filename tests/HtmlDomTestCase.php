<?php

declare(strict_types=1);

namespace cinghie\adminlte3\tests;

use DOMDocument;
use DOMElement;
use DOMNodeList;
use DOMXPath;
use PHPUnit\Framework\TestCase;

/**
 * Base test case for structural assertions against widget HTML fragments.
 */
abstract class HtmlDomTestCase extends TestCase
{
    protected function xpath(string $html): DOMXPath
    {
        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);

        try {
            $loaded = $document->loadHTML(
                '<?xml encoding="UTF-8"><div id="test-root">' . $html . '</div>',
                LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
            );
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }

        self::assertTrue($loaded, 'Widget HTML should be parseable by DOMDocument.');

        return new DOMXPath($document);
    }

    protected function one(DOMXPath $xpath, string $expression): DOMElement
    {
        $nodes = $xpath->query($expression);
        self::assertInstanceOf(DOMNodeList::class, $nodes);
        self::assertSame(1, $nodes->length, 'Expected exactly one node for XPath: ' . $expression);

        $node = $nodes->item(0);
        self::assertInstanceOf(DOMElement::class, $node);

        return $node;
    }

    protected function hasClass(DOMElement $element, string $class): bool
    {
        $classes = preg_split('/\s+/', trim($element->getAttribute('class')), -1, PREG_SPLIT_NO_EMPTY);

        return in_array($class, $classes ?: [], true);
    }
}
