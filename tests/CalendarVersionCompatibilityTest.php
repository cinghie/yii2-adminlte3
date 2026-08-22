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
}
