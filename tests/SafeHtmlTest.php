<?php

declare(strict_types=1);

namespace cinghie\adminlte3\tests;

use cinghie\adminlte3\widgets\support\SafeHtml;
use PHPUnit\Framework\TestCase;

/**
 * Regression coverage for the internal HTML-attribute safety policy.
 */
final class SafeHtmlTest extends TestCase
{
    public function testCssClassRemovesAttributeBreakingCharacters(): void
    {
        self::assertSame('fas fa-home onclickalert1', SafeHtml::cssClass('fas fa-home" onclick="alert(1)'));
        self::assertSame('fallback', SafeHtml::cssClass('<>"\'', 'fallback'));
    }

    public function testDangerousUnknownAndMalformedUrlSchemesAreRejected(): void
    {
        self::assertSame('#', SafeHtml::linkUrl('javascript:alert(1)'));
        self::assertSame('#', SafeHtml::linkUrl('data:text/html,test'));
        self::assertSame('#', SafeHtml::linkUrl('ftp://example.com/file'));
        self::assertSame('#', SafeHtml::linkUrl('https:///missing-host'));
        self::assertSame('#', SafeHtml::linkUrl('//example.com/path'));
        self::assertSame('https://example.com/path', SafeHtml::linkUrl('https://example.com/path'));
        self::assertSame('/site/index', SafeHtml::linkUrl('/site/index'));
    }

    public function testHttpAndEmailValidationAreStrict(): void
    {
        self::assertSame('https://example.com/path', SafeHtml::httpUrl('https://example.com/path'));
        self::assertNull(SafeHtml::httpUrl('https:///missing-host'));
        self::assertSame('mailto:user@example.com', SafeHtml::emailHref('user@example.com'));
        self::assertNull(SafeHtml::emailHref('not-an-email'));
    }

    public function testBlankTargetGetsNoopenerAndNoreferrer(): void
    {
        self::assertSame(
            ['target' => '_blank', 'rel' => 'noopener noreferrer'],
            SafeHtml::externalLinkOptions('_blank')
        );
        self::assertSame([], SafeHtml::externalLinkOptions(null));
    }
}
