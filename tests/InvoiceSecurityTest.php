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

    public function testBankFieldsRenderEncodedInMeta(): void
    {
        $html = Invoice::widget([
            'invoicePaymentMethod' => 'Bonifico',
            'invoicePaymentMethodCode' => 'MP05',
            'invoiceBankName' => '<Bank>',
            'invoiceBankIban' => 'IT60X0542811101000000123456',
            'invoiceBankSwift' => 'BPMOIT22XXX',
            'showActions' => false,
        ]);

        self::assertStringContainsString('&lt;Bank&gt;', $html);
        self::assertStringNotContainsString('<Bank>', $html);
        self::assertStringContainsString('IT60X0542811101000000123456', $html);
        self::assertStringContainsString('BPMOIT22XXX', $html);
        self::assertStringContainsString('Bonifico', $html);
        self::assertStringContainsString('invoice-bank', $html);
        self::assertSame(1, substr_count($html, 'invoice-bank'));
        self::assertSame(1, substr_count($html, 'IT60X0542811101000000123456'));
        self::assertStringContainsString('p-3', $html);
        self::assertStringContainsString('mb-3', $html);
    }

    public function testInvoiceAccountAndMetaAreEncoded(): void
    {
        $html = Invoice::widget([
            'invoiceAccount' => '<Acme>',
            'invoiceMeta' => [
                ['label' => '<CIG>', 'value' => '<Z1>', 'href' => 'javascript:alert(1)'],
                ['label' => 'CUP', 'value' => 'CUP-9', 'href' => 'https://example.com/cup'],
                ['Skip empty', ''],
            ],
            'showActions' => false,
        ]);

        self::assertStringContainsString('&lt;Acme&gt;', $html);
        self::assertStringContainsString('&lt;CIG&gt;', $html);
        self::assertStringContainsString('&lt;Z1&gt;', $html);
        self::assertStringNotContainsString('<Acme>', $html);
        self::assertStringNotContainsString('<CIG>', $html);
        self::assertSame(0, $this->xpath($html)->query("//*[@id='test-root']//a[starts-with(@href, 'javascript:')]")->length);
        self::assertStringContainsString('CUP-9', $html);
        $link = $this->one($this->xpath($html), "//*[@id='test-root']//a[@href='https://example.com/cup']");
        self::assertSame('_blank', $link->getAttribute('target'));
    }
}
