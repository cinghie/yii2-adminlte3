<?php

declare(strict_types=1);

namespace cinghie\adminlte3\tests;

use PHPUnit\Framework\TestCase;

final class CalendarVersionCompatibilityTest extends TestCase
{
    public function testInitializerNormalizesLegacyAndModernOptionNames(): void
    {
        $source = file_get_contents(dirname(__DIR__) . '/assets/js/calendar.js');
        self::assertIsString($source);

        self::assertStringContainsString('function normalizeVersionOptions(options)', $source);
        self::assertStringContainsString("version.indexOf('4.') === 0", $source);
        self::assertStringContainsString('options.header = options.headerToolbar', $source);
        self::assertStringContainsString('options.defaultView = options.initialView', $source);
        self::assertStringContainsString('options.defaultDate = options.initialDate', $source);
        self::assertStringContainsString('delete options.headerToolbar', $source);
        self::assertStringContainsString('delete options.initialView', $source);
        self::assertStringContainsString('delete options.initialDate', $source);

        self::assertStringContainsString('options.headerToolbar = options.header', $source);
        self::assertStringContainsString('options.initialView = options.defaultView', $source);
        self::assertStringContainsString('options.initialDate = options.defaultDate', $source);
        self::assertStringContainsString('delete options.header;', $source);
        self::assertStringContainsString('delete options.defaultView;', $source);
        self::assertStringContainsString('delete options.defaultDate;', $source);

        self::assertStringContainsString('options = normalizeVersionOptions(options);', $source);
        self::assertStringContainsString('options = applyLegacyDefaults(options);', $source);
    }

    public function testInitializerUsesOneSetOfGlobalResizeHandlers(): void
    {
        $source = file_get_contents(dirname(__DIR__) . '/assets/js/calendar.js');
        self::assertIsString($source);

        self::assertStringContainsString('var globalHandlersRegistered = false;', $source);
        self::assertStringContainsString('function updateAllCalendars()', $source);
        self::assertStringContainsString('function registerGlobalResizeHandlers()', $source);
        self::assertStringContainsString("window.addEventListener('resize', updateAllCalendars)", $source);
        self::assertStringContainsString("document.addEventListener('collapsed.lte.pushmenu', updateAllCalendars)", $source);
        self::assertStringContainsString("document.addEventListener('shown.lte.pushmenu', updateAllCalendars)", $source);
        self::assertStringContainsString('registerGlobalResizeHandlers();', $source);
        self::assertSame(1, substr_count($source, "window.addEventListener('resize'"));
        self::assertSame(1, substr_count($source, "document.addEventListener('collapsed.lte.pushmenu'"));
        self::assertSame(1, substr_count($source, "document.addEventListener('shown.lte.pushmenu'"));
    }
}
