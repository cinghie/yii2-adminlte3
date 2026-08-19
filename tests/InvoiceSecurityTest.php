<?php

declare(strict_types=1);

namespace cinghie\adminlte3\tests;

use cinghie\adminlte3\widgets\Invoice;
use PHPUnit\Framework\TestCase;

final class InvoiceSecurityTest extends TestCase
{
    public function testDangerousWebsiteDoesNotBecomeLink(): void
    {
        $html = Invoice::widget([
            'invoiceFromWebsite' => 'javascript:alert(1)',
            'showActions' => false,
        ]);

        $this->assertStringNotContainsString('href="javascript:', $html);
    }

    public function testRemoteCompanyLogoIsBlockedByDefault(): void
    {
        $url = 'https://tracker.example/logo.png';
        $html = Invoice::widget([
            'companyLogo' => $url,
            'showActions' => false,
        ]);

        $this->assertStringNotContainsString($url, $html);
        $this->assertStringContainsString('fas fa-globe', $html);
    }

    public function testRemoteCompanyLogoCanBeExplicitlyEnabled(): void
    {
        $url = 'https://example.com/logo.png';
        $html = Invoice::widget([
            'companyLogo' => $url,
            'allowRemoteCompanyLogo' => true,
            'showActions' => false,
        ]);

        $this->assertStringContainsString($url, $html);
    }

    public function testInvalidEmailDoesNotBecomeMailtoLink(): void
    {
        $html = Invoice::widget([
            'invoiceFromEmail' => 'not-an-email',
            'showActions' => false,
        ]);

        $this->assertStringNotContainsString('mailto:', $html);
    }

    public function testCustomJavascriptPrintUrlIsRejected(): void
    {
        $html = Invoice::widget([
            'printUrl' => 'javascript:alert(1)',
        ]);

        $this->assertStringNotContainsString('href="javascript:', $html);
        $this->assertStringNotContainsString('alert(1)', $html);
    }

    public function testDefaultPrintUsesFixedHandler(): void
    {
        $html = Invoice::widget();

        $this->assertStringContainsString('window.print()', $html);
        $this->assertStringContainsString('href="#"', $html);
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

        $this->assertStringContainsString('rel="noopener noreferrer"', $html);
    }
}
