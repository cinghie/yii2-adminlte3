<?php

declare(strict_types=1);

namespace cinghie\adminlte3\tests;

use cinghie\adminlte3\widgets\ColorPicker;
use cinghie\adminlte3\widgets\DateTimePicker;
use cinghie\adminlte3\widgets\InfoBox;
use cinghie\adminlte3\widgets\Invoice;
use cinghie\adminlte3\widgets\MailboxRead;
use cinghie\adminlte3\widgets\SidebarMenu;
use PHPUnit\Framework\TestCase;

/**
 * Guards package-owned markup against avoidable inline script/style patterns.
 */
final class CspCompatibilityTest extends TestCase
{
    public function testInvoiceDefaultPrintActionHasNoInlineHandler(): void
    {
        $html = Invoice::widget([
            'companyName' => 'Example',
            'invoiceNumber' => 'INV-1',
            'showActions' => true,
        ]);

        self::assertStringContainsString('data-cinghie-action="print"', $html);
        self::assertStringNotContainsString('onclick=', $html);
        self::assertStringNotContainsString('javascript:', $html);
        self::assertStringNotContainsString('style=', $html);
    }

    public function testMailboxAttachmentsDoNotUseInlineStyle(): void
    {
        $html = MailboxRead::widget([
            'mailAttachments' => [[
                'url' => '/files/report.pdf',
                'filename' => 'report.pdf',
                'size' => '10 KB',
            ]],
        ]);

        self::assertStringNotContainsString('style=', $html);
        self::assertStringNotContainsString('onclick=', $html);
    }

    public function testInfoBoxProgressUsesStaticWidthClassWithoutInlineStyle(): void
    {
        $html = InfoBox::widget([
            'text' => 'Progress',
            'number' => '37%',
            'progress' => 37,
        ]);

        self::assertStringContainsString('cinghie-progress-width-37', $html);
        self::assertStringContainsString('aria-valuenow="37"', $html);
        self::assertStringNotContainsString('style=', $html);
    }

    public function testInfoBoxProgressStillClampsToSupportedRange(): void
    {
        $below = InfoBox::widget(['progress' => -10]);
        $above = InfoBox::widget(['progress' => 150]);

        self::assertStringContainsString('cinghie-progress-width-0', $below);
        self::assertStringContainsString('aria-valuenow="0"', $below);
        self::assertStringContainsString('cinghie-progress-width-100', $above);
        self::assertStringContainsString('aria-valuenow="100"', $above);
        self::assertStringNotContainsString('style=', $below);
        self::assertStringNotContainsString('style=', $above);
    }

    public function testReusableInputWidgetsDoNotEmitPackageOwnedInlineCode(): void
    {
        $color = ColorPicker::widget(['name' => 'color', 'value' => '#3c8dbc']);
        $dateTime = DateTimePicker::widget(['name' => 'starts_at', 'value' => '2026-08-23 19:30:00']);

        foreach ([$color, $dateTime] as $html) {
            self::assertStringNotContainsString('<script', $html);
            self::assertStringNotContainsString('<style', $html);
            self::assertStringNotContainsString('onclick=', $html);
            self::assertStringNotContainsString('style=', $html);
        }
    }

    public function testActiveSidebarUsesMenuClassInsteadOfInlineDisplayStyle(): void
    {
        $html = SidebarMenu::widget([
            'route' => 'site/index',
            'params' => [],
            'items' => [[
                'label' => 'Parent',
                'items' => [[
                    'label' => 'Dashboard',
                    'url' => ['/site/index'],
                ]],
            ]],
        ]);

        self::assertStringContainsString('menu-open', $html);
        self::assertStringNotContainsString('style="display:', $html);
        self::assertStringNotContainsString('onclick=', $html);
    }

    public function testExternalWidgetScriptContainsPrintDelegation(): void
    {
        $script = file_get_contents(dirname(__DIR__) . '/assets/js/widgets.js');

        self::assertIsString($script);
        self::assertStringContainsString('data-cinghie-action="print"', $script);
        self::assertStringContainsString('window.print()', $script);
    }
}
