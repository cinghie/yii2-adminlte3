<?php

declare(strict_types=1);

namespace cinghie\adminlte3\tests;

use PHPUnit\Framework\Attributes\DataProvider;
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

    /**
     * @dataProvider publicWidgetProvider
     */
    public function testPublicWidgetHasClassDocumentation(string $class): void
    {
        self::assertTrue(class_exists($class), $class . ' must autoload.');

        $reflection = new ReflectionClass($class);
        $doc = $reflection->getDocComment();

        self::assertIsString($doc, $class . ' must have a class-level PHPDoc block.');
        self::assertNotSame('', trim((string) $doc), $class . ' PHPDoc must not be empty.');
    }
}
