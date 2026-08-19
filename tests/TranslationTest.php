<?php

declare(strict_types=1);

namespace cinghie\adminlte3\tests;

use cinghie\adminlte3\widgets\support\Translation;
use FilesystemIterator;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Yii;
use yii\i18n\PhpMessageSource;

/**
 * Guards package-owned translations and the explicit legacy Timeline fallback.
 */
final class TranslationTest extends TestCase
{
    /** @var mixed Original adminlte3 translation configuration. */
    private $originalTranslation;

    /** @var bool Whether the category existed before the current test. */
    private bool $hadOriginalTranslation = false;

    protected function setUp(): void
    {
        parent::setUp();
        $translations = Yii::$app->i18n->translations;
        $this->hadOriginalTranslation = array_key_exists(Translation::CATEGORY, $translations);
        $this->originalTranslation = $translations[Translation::CATEGORY] ?? null;
        unset(Yii::$app->i18n->translations[Translation::CATEGORY]);
    }

    protected function tearDown(): void
    {
        if ($this->hadOriginalTranslation) {
            Yii::$app->i18n->translations[Translation::CATEGORY] = $this->originalTranslation;
        } else {
            unset(Yii::$app->i18n->translations[Translation::CATEGORY]);
        }
        parent::tearDown();
    }

    public function testPackageTranslationSourceRegistersLazily(): void
    {
        self::assertSame('Search', Translation::t('Search'));
        self::assertArrayHasKey(Translation::CATEGORY, Yii::$app->i18n->translations);
    }

    public function testApplicationTranslationOverrideIsPreserved(): void
    {
        Yii::$app->i18n->translations[Translation::CATEGORY] = [
            'class' => PhpMessageSource::class,
            'basePath' => __DIR__ . '/messages',
            'sourceLanguage' => 'en-US',
        ];

        // Yii returns source text directly when target language equals the source
        // language, so use a distinct target to exercise the configured catalog.
        self::assertSame('Ricerca personalizzata', Translation::t('Search', [], 'it-IT'));
    }

    public function testPackageWidgetsDoNotDependOnLegacyUiTranslationCategories(): void
    {
        $violations = [];
        $root = dirname(__DIR__) . '/widgets';
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            /** @var SplFileInfo $file */
            if (!$file->isFile() || $file->getExtension() !== 'php' || $file->getFilename() === 'Timeline.php') {
                continue;
            }

            $source = file_get_contents($file->getPathname());
            if ($source !== false && preg_match('/Yii::t\(\s*[\'\"](?:app|crm|traits)[\'\"]/', $source) === 1) {
                $violations[] = str_replace('\\', '/', substr($file->getPathname(), strlen(dirname(__DIR__)) + 1));
            }
        }

        self::assertSame(
            [],
            $violations,
            'Package-owned widget UI must use the adminlte3 translation category: ' . implode(', ', $violations)
        );
    }

    public function testTimelineLegacyDomainTranslationIsExplicitlyIsolated(): void
    {
        $source = file_get_contents(dirname(__DIR__) . '/widgets/Timeline.php');

        self::assertIsString($source);
        self::assertMatchesRegularExpression('/Yii::t\(\s*[\'\"]traits[\'\"]\s*,\s*\$item->action\s*\)/', $source);
    }
}
