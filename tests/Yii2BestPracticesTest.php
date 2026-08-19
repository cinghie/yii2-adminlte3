<?php

declare(strict_types=1);

namespace cinghie\adminlte3\tests;

use FilesystemIterator;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * Guards source-level Yii 2 and PSR-12 practices that are useful for this package.
 *
 * PSR-12 defines the position and ordering of class, function and constant import
 * blocks. It does not require alphabetical sorting inside those blocks, so this
 * test deliberately avoids imposing a project-specific alphabetical rule.
 */
final class Yii2BestPracticesTest extends TestCase
{
    public function testPhpSourcesDoNotUseTabsForIndentation(): void
    {
        $violations = [];

        foreach ($this->phpFiles() as $path => $source) {
            if (preg_match('/^\t+/m', $source) === 1) {
                $violations[] = $path;
            }
        }

        self::assertSame([], $violations, 'PHP source must use four spaces, not tabs: ' . implode(', ', $violations));
    }

    public function testUseYiiImportIsNotImproperOrUnused(): void
    {
        $improper = [];
        $unused = [];

        foreach ($this->phpFiles() as $path => $source) {
            if (preg_match('/^use\s+\\\\?Yii\s*;/m', $source) !== 1) {
                continue;
            }

            if (preg_match('/^namespace\s+/m', $source) !== 1) {
                $improper[] = $path;
                continue;
            }

            if (preg_match('/(?<![\w\\\\])Yii::/', $source) !== 1) {
                $unused[] = $path;
            }
        }

        self::assertSame(
            [],
            $improper,
            'Do not import Yii in non-namespaced PHP files: ' . implode(', ', $improper)
        );
        self::assertSame([], $unused, '`use Yii;` is unused in: ' . implode(', ', $unused));
    }

    public function testUseStatementGroupsFollowPsr12Order(): void
    {
        $violations = [];

        foreach ($this->phpFiles() as $path => $source) {
            $ranks = [];
            foreach ($this->topLevelUseStatements($source) as $statement) {
                if (str_starts_with($statement, 'function ')) {
                    $ranks[] = 1;
                } elseif (str_starts_with($statement, 'const ')) {
                    $ranks[] = 2;
                } else {
                    $ranks[] = 0;
                }
            }

            $expected = $ranks;
            sort($expected);
            if ($ranks !== $expected) {
                $violations[] = $path;
            }
        }

        self::assertSame(
            [],
            $violations,
            'PSR-12 requires class imports before function imports and function imports before constant imports: '
                . implode(', ', $violations)
        );
    }

    /**
     * Returns package PHP source keyed by repository-relative path.
     *
     * @return array<string,string>
     */
    private function phpFiles(): array
    {
        $root = dirname(__DIR__);
        $files = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            /** @var SplFileInfo $file */
            if (!$file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            $path = str_replace('\\', '/', substr($file->getPathname(), strlen($root) + 1));
            if (str_starts_with($path, 'vendor/') || str_starts_with($path, 'runtime/')) {
                continue;
            }

            $source = file_get_contents($file->getPathname());
            if ($source !== false) {
                $files[$path] = $source;
            }
        }

        return $files;
    }

    /**
     * Extracts top-level imports while ignoring trait-use statements inside classes.
     *
     * @param string $source PHP source.
     * @return string[]
     */
    private function topLevelUseStatements(string $source): array
    {
        $statements = [];
        $tokens = token_get_all($source);
        $braceDepth = 0;
        $namespaceSeen = false;

        for ($i = 0, $count = count($tokens); $i < $count; $i++) {
            $token = $tokens[$i];
            if ($token === '{') {
                $braceDepth++;
                continue;
            }
            if ($token === '}') {
                $braceDepth--;
                continue;
            }
            if (!is_array($token)) {
                continue;
            }
            if ($token[0] === T_NAMESPACE) {
                $namespaceSeen = true;
                continue;
            }
            if ($token[0] !== T_USE || !$namespaceSeen || $braceDepth !== 0) {
                continue;
            }

            $statement = '';
            for ($i++; $i < $count; $i++) {
                $part = $tokens[$i];
                if ($part === ';') {
                    break;
                }
                $statement .= is_array($part) ? $part[1] : $part;
            }
            $statements[] = trim(preg_replace('/\s+/', ' ', $statement) ?? $statement);
        }

        return $statements;
    }
}
