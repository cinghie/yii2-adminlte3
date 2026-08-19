<?php

declare(strict_types=1);

namespace cinghie\adminlte3\tests;

use FilesystemIterator;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * Prevents historical package/version/license headers from being reintroduced.
 */
final class SourceMetadataTest extends TestCase
{
    public function testSourceFilesDoNotContainLegacyMetadataHeaders(): void
    {
        $root = dirname(__DIR__);
        $paths = [
            $root,
            $root . '/assets',
            $root . '/widgets',
        ];
        $forbidden = [
            '@company',
            '@website',
            '@github',
            '@package yii2-AdminLTE',
            '@version 0.1.0',
            'GNU GENERAL PUBLIC LICENSE VERSION 3',
        ];
        $violations = [];

        foreach ($paths as $path) {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
                /** @var SplFileInfo $file */
                if (!$file->isFile() || $file->getExtension() !== 'php') {
                    continue;
                }

                $pathname = $file->getPathname();
                if (str_contains($pathname, DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR)
                    || str_contains($pathname, DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR)
                    || str_contains($pathname, DIRECTORY_SEPARATOR . 'runtime' . DIRECTORY_SEPARATOR)) {
                    continue;
                }

                $source = file_get_contents($pathname);
                if ($source === false) {
                    continue;
                }

                foreach ($forbidden as $needle) {
                    if (str_contains($source, $needle)) {
                        $violations[] = str_replace('\\', '/', substr($pathname, strlen($root) + 1)) . ': ' . $needle;
                    }
                }
            }
        }

        self::assertSame([], array_values(array_unique($violations)), implode("\n", $violations));
    }
}
