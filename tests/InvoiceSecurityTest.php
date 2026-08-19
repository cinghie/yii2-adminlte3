<?php

declare(strict_types=1);

namespace cinghie\adminlte3\tests;

use cinghie\adminlte3\widgets\Invoice;
use PHPUnit\Framework\TestCase;

/**
 * Security regression coverage for Invoice-generated URLs and actions.
 */
final class InvoiceSecurityTest extends TestCase
{
    public function testDangerousWebsiteDoesNotBecomeLink(): void
    {
        $html = Invoice::widget([
            'invoiceFromWebsite' => 'javascript:alert(1)',
            'showActions' => false,
        ]);

        self::assertStringNotContainsString('href="javascript:', $html);
    }

    public function testRemoteCompanyLogoIsBlockedByDefault(): void
    {
        $url = 'https://tracker.example/logo.png';
        $html = Invoice::widget([
            'companyLogo' => $url,
            'showActions' => false,
        ]);

        self::assertStringNotContainsString($url, $html);
        self::assertStringContainsString('fas fa-globe', $html);
    }

    public function testRemoteCompanyLogoCanBeExplicitlyEnabled(): void
    {
        $url = 'https://example.com/logo.png';
        $html = Invoice::widget([
            'companyLogo' => $url,
            'allowRemoteCompanyLogo' => true,
            'showActions' => false,
        ]);

        self::assertStringContainsString($url, $html);
    }

    public function testInvalidEmailDoesNotBecomeMailtoLink(): void
    {
        $html = Invoice::widget([
            'invoiceFromEmail' => 'not-an-email',
            'showActions' => false,
        ]);

        self::assertStringNotContainsString('mailto:', $html);
    }

    public function testCustomJavascriptPrintUrlIsRejected(): void
    {
        $html = Invoice::widget([
            'printUrl' => 'javascript:alert(1)',
        ]);

        self::assertStringNotContainsString('href="javascript:', $html);
        self::assertStringNotContainsString('alert(1)', $html);
    }

    public function testDefaultPrintUsesCspFriendlyDataAction(): void
    {
        $html = Invoice::widget();

        self::assertStringContainsString('href="#"', $html);
        self::assertStringContainsString('data-cinghie-action="print"', $html);
        self::assertStringNotContainsString('onclick=', $html);
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

        self::assertStringContainsString('rel="noopener noreferrer"', $html);
    }
}
