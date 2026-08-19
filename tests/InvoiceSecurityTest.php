<?php

declare(strict_types=1);

namespace cinghie\adminlte3\tests;

use cinghie\adminlte3\widgets\Invoice;

/**
 * Security regression coverage for Invoice-generated URLs and actions.
 */
final class InvoiceSecurityTest extends HtmlDomTestCase
{
    public function testDangerousWebsiteDoesNotBecomeLink(): void
    {
        $html = Invoice::widget([
            'invoiceFromWebsite' => 'javascript:alert(1)',
            'showActions' => false,
        ]);

        self::assertSame(0, $this->xpath($html)->query("//*[@id='test-root']//a[starts-with(@href, 'javascript:')]")->length);
    }

    public function testRemoteCompanyLogoIsBlockedByDefault(): void
    {
        $url = 'https://tracker.example/logo.png';
        $html = Invoice::widget([
            'companyLogo' => $url,
            'showActions' => false,
        ]);

        $xpath = $this->xpath($html);
        self::assertSame(0, $xpath->query("//*[@id='test-root']//img[@src='" . $url . "']")->length);
        self::assertSame(1, $xpath->query("//*[@id='test-root']//i[contains(concat(' ', normalize-space(@class), ' '), ' fa-globe ')]")->length);
    }

    public function testRemoteCompanyLogoCanBeExplicitlyEnabled(): void
    {
        $url = 'https://example.com/logo.png';
        $html = Invoice::widget([
            'companyLogo' => $url,
            'allowRemoteCompanyLogo' => true,
            'showActions' => false,
        ]);

        $logo = $this->one($this->xpath($html), "//*[@id='test-root']//img[@src='" . $url . "']");
        self::assertSame($url, $logo->getAttribute('src'));
    }

    public function testInvalidEmailDoesNotBecomeMailtoLink(): void
    {
        $html = Invoice::widget([
            'invoiceFromEmail' => 'not-an-email',
            'showActions' => false,
        ]);

        self::assertSame(0, $this->xpath($html)->query("//*[@id='test-root']//a[starts-with(@href, 'mailto:')]")->length);
    }

    public function testCustomJavascriptPrintUrlIsRejected(): void
    {
        $html = Invoice::widget([
            'printUrl' => 'javascript:alert(1)',
        ]);

        $xpath = $this->xpath($html);
        self::assertSame(0, $xpath->query("//*[@id='test-root']//a[starts-with(@href, 'javascript:')]")->length);
        self::assertStringNotContainsString('alert(1)', $html);
    }

    public function testDefaultPrintUsesCspFriendlyDataAction(): void
    {
        $html = Invoice::widget();

        $xpath = $this->xpath($html);
        $print = $this->one($xpath, "//*[@id='test-root']//a[@data-cinghie-action='print']");

        self::assertSame('#', $print->getAttribute('href'));
        self::assertFalse($print->hasAttribute('onclick'));
        self::assertStringNotContainsString('window.print()', $html);
    }

    public function testAutolinkUsesNoopener(): void
    {
        $html = Invoice::widget([
            'invoiceItems' => [[
                'name' => 'Service',
                'description' => 'https://example.com/docs',
                'quantity' => 1,
                'subtotal' => '10.00',
            ]],
            'showActions' => false,
        ]);

        $link = $this->one($this->xpath($html), "//*[@id='test-root']//a[@href='https://example.com/docs']");
        $rel = preg_split('/\s+/', trim($link->getAttribute('rel')), -1, PREG_SPLIT_NO_EMPTY) ?: [];

        self::assertContains('noopener', $rel);
        self::assertContains('noreferrer', $rel);
        self::assertSame('_blank', $link->getAttribute('target'));
    }
}
