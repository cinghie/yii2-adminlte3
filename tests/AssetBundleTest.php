<?php

declare(strict_types=1);

namespace cinghie\adminlte3\tests;

use cinghie\adminlte3\AdminLTEAsset;
use cinghie\adminlte3\AdminLTECoreAsset;
use cinghie\adminlte3\AdminLTECoreMinifyAsset;
use cinghie\adminlte3\AdminLTEDateTimeAsset;
use cinghie\adminlte3\AdminLTEDateTimeMinifyAsset;
use cinghie\adminlte3\AdminLTEIcheckAsset;
use cinghie\adminlte3\AdminLTEIcheckMinifyAsset;
use cinghie\adminlte3\AdminLTEJqueryUiAsset;
use cinghie\adminlte3\AdminLTEJqueryUiMinifyAsset;
use cinghie\adminlte3\AdminLTEMinifyAsset;
use cinghie\adminlte3\assets\AdminLTEThemeAsset;
use cinghie\fontawesome\FontAwesomeAsset;
use cinghie\fontawesome\FontAwesomeMinifyAsset;
use PHPUnit\Framework\TestCase;
use yii\bootstrap4\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;
use yii\web\YiiAsset;

final class AssetBundleTest extends TestCase
{
    public function coreAssetProvider(): array
    {
        return [
            [new AdminLTECoreAsset()],
            [new AdminLTECoreMinifyAsset()],
        ];
    }

    /**
     * @dataProvider coreAssetProvider
     */
    public function testCoreBundleDoesNotPullOptionalPlugins(AssetBundle $asset): void
    {
        self::assertContains(YiiAsset::class, $asset->depends);
        self::assertContains(BootstrapAsset::class, $asset->depends);
        self::assertNotContains(JqueryAsset::class, $asset->depends);
        self::assertTrue($asset->appendTimestamp);
        self::assertCount(1, $asset->css);
        self::assertCount(1, $asset->js);
        self::assertStringContainsString('dist/css/adminlte', $asset->css[0]);
        self::assertStringContainsString('dist/js/adminlte', $asset->js[0]);

        foreach (array_merge($asset->css, $asset->js) as $file) {
            self::assertStringNotContainsString('plugins/', $file);
        }
    }

    public function testAggregateBundlesPreserveHistoricalPluginFamilies(): void
    {
        self::assertSame([
            AdminLTEJqueryUiAsset::class,
            AdminLTEDateTimeAsset::class,
            AdminLTEIcheckAsset::class,
            AdminLTECoreAsset::class,
        ], (new AdminLTEAsset())->depends);

        self::assertSame([
            AdminLTEJqueryUiMinifyAsset::class,
            AdminLTEDateTimeMinifyAsset::class,
            AdminLTEIcheckMinifyAsset::class,
            AdminLTECoreMinifyAsset::class,
        ], (new AdminLTEMinifyAsset())->depends);
    }

    public function parityProvider(): array
    {
        return [
            'aggregate' => [new AdminLTEAsset(), new AdminLTEMinifyAsset()],
            'core' => [new AdminLTECoreAsset(), new AdminLTECoreMinifyAsset()],
            'jquery-ui' => [new AdminLTEJqueryUiAsset(), new AdminLTEJqueryUiMinifyAsset()],
            'date-time' => [new AdminLTEDateTimeAsset(), new AdminLTEDateTimeMinifyAsset()],
            'icheck' => [new AdminLTEIcheckAsset(), new AdminLTEIcheckMinifyAsset()],
        ];
    }

    /**
     * @dataProvider parityProvider
     */
    public function testMinifiedAndSourceBundlesHaveSemanticParity(AssetBundle $source, AssetBundle $minified): void
    {
        self::assertSame(
            array_map([$this, 'normalizeAssetPath'], $source->css),
            array_map([$this, 'normalizeAssetPath'], $minified->css)
        );
        self::assertSame(
            array_map([$this, 'normalizeAssetPath'], $source->js),
            array_map([$this, 'normalizeAssetPath'], $minified->js)
        );
        self::assertSame(
            array_map([$this, 'normalizeDependency'], $source->depends),
            array_map([$this, 'normalizeDependency'], $minified->depends)
        );
        self::assertSame($source->appendTimestamp, $minified->appendTimestamp);
    }

    public function testAllDeclaredAdminlteSourceFilesExist(): void
    {
        $bundles = [
            new AdminLTECoreAsset(),
            new AdminLTECoreMinifyAsset(),
            new AdminLTEJqueryUiAsset(),
            new AdminLTEJqueryUiMinifyAsset(),
            new AdminLTEDateTimeAsset(),
            new AdminLTEDateTimeMinifyAsset(),
            new AdminLTEIcheckAsset(),
            new AdminLTEIcheckMinifyAsset(),
        ];

        $vendorRoot = dirname(__DIR__) . '/vendor/almasaeed2010/adminlte/';
        foreach ($bundles as $bundle) {
            foreach (array_merge($bundle->css, $bundle->js) as $file) {
                self::assertFileExists($vendorRoot . $file, get_class($bundle) . ' references missing asset ' . $file);
            }
        }
    }

    public function testThemeAssetPublishesCspSafeProgressWidths(): void
    {
        $asset = new AdminLTEThemeAsset();

        self::assertContains('css/progress-widths.css', $asset->css);

        $css = file_get_contents(dirname(__DIR__) . '/assets/css/progress-widths.css');
        self::assertIsString($css);
        self::assertStringContainsString('.cinghie-progress-width-0 { width: 0%; }', $css);
        self::assertStringContainsString('.cinghie-progress-width-37 { width: 37%; }', $css);
        self::assertStringContainsString('.cinghie-progress-width-100 { width: 100%; }', $css);
    }

    private function normalizeAssetPath(string $path): string
    {
        return preg_replace('/\.min(?=\.(?:css|js)$)/', '', $path) ?? $path;
    }

    private function normalizeDependency(string $dependency): string
    {
        $map = [
            AdminLTEMinifyAsset::class => AdminLTEAsset::class,
            AdminLTECoreMinifyAsset::class => AdminLTECoreAsset::class,
            AdminLTEJqueryUiMinifyAsset::class => AdminLTEJqueryUiAsset::class,
            AdminLTEDateTimeMinifyAsset::class => AdminLTEDateTimeAsset::class,
            AdminLTEIcheckMinifyAsset::class => AdminLTEIcheckAsset::class,
            FontAwesomeMinifyAsset::class => FontAwesomeAsset::class,
        ];

        return $map[$dependency] ?? $dependency;
    }
}
