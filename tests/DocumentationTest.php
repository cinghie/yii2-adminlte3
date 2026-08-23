<?php

declare(strict_types=1);

namespace cinghie\adminlte3\tests;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Ensures every package widget exposes a useful class-level PHPDoc contract.
 */
final class DocumentationTest extends TestCase
{
    /**
     * @return iterable<string,array{0:string}>
     */
    public static function publicWidgetProvider(): iterable
    {
        foreach (glob(dirname(__DIR__) . '/widgets/*.php') ?: [] as $path) {
            $class = 'cinghie\\adminlte3\\widgets\\' . basename($path, '.php');
            yield $class => [$class];
        }
    }

    /** @dataProvider publicWidgetProvider */
    public function testPublicWidgetHasClassDocumentation(string $class): void
    {
        self::assertTrue(class_exists($class), $class . ' must autoload.');
        $reflection = new ReflectionClass($class);
        $doc = $reflection->getDocComment();
        self::assertIsString($doc, $class . ' must have a class-level PHPDoc block.');
        self::assertNotSame('', trim((string) $doc), $class . ' PHPDoc must not be empty.');
    }

    public function testDateTimePickerHardeningIsDocumentedWithoutDroppingRoadmapHistory(): void
    {
        $root = dirname(__DIR__);
        $update = file_get_contents($root . '/UPDATE.md');
        $changelog = file_get_contents($root . '/CHANGELOG.md');

        foreach (['2026-08-23', 'DateTimePicker', 'BootstrapPluginAsset', 'Tempus Dominus', 'rendered-widget'] as $expected) {
            self::assertStringContainsString($expected, $update, $expected);
            self::assertStringContainsString($expected, $changelog, $expected);
        }

        foreach (['Possible future expansions', 'History / operations', '2026-08-19', 'Visual regression / browser smoke testing'] as $expected) {
            self::assertStringContainsString($expected, $update, $expected);
        }

        self::assertStringContainsString('## 2026-08-22', $changelog);
        self::assertStringContainsString('## 2026-08-19', $changelog);
        self::assertStringContainsString('## 2026-07-30', $changelog);
    }

    public function testReusableInputWidgetsAreLinkedAndExplained(): void
    {
        $root = dirname(__DIR__);
        $readme = file_get_contents($root . '/README.md');
        $guide = file_get_contents($root . '/docs/example_inputwidgets.md');

        foreach (['Reusable input widgets', 'ColorPicker', 'DatePicker', 'DateTimePicker', 'docs/example_inputwidgets.md'] as $expected) {
            self::assertStringContainsString($expected, $readme, $expected);
        }
        foreach (['ColorPicker', 'DatePicker', 'DateTimePicker', 'Tempus Dominus'] as $expected) {
            self::assertStringContainsString($expected, $guide, $expected);
        }
    }
}
