<?php

declare(strict_types=1);

namespace cinghie\adminlte3\tests;

use cinghie\adminlte3\AdminLTEAsset;
use cinghie\adminlte3\AdminLTECalendarAsset;
use cinghie\adminlte3\AdminLTECalendarMinifyAsset;
use cinghie\adminlte3\AdminLTEChartJSAsset;
use cinghie\adminlte3\AdminLTEChartJSMinifyAsset;
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
use cinghie\adminlte3\assets\CalendarWidgetAsset;
use cinghie\adminlte3\assets\ChartJSWidgetAsset;
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

        self::assertNotContains(AdminLTECalendarAsset::class, (new AdminLTEAsset())->depends);
        self::assertNotContains(AdminLTEChartJSAsset::class, (new AdminLTEAsset())->depends);
        self::assertNotContains(AdminLTECalendarMinifyAsset::class, (new AdminLTEMinifyAsset())->depends);
        self::assertNotContains(AdminLTEChartJSMinifyAsset::class, (new AdminLTEMinifyAsset())->depends);
    }

    public function testAdditionalWidgetInitializersKeepHeavyPluginsOptional(): void
    {
        self::assertSame([AdminLTECalendarMinifyAsset::class], (new CalendarWidgetAsset())->depends);
        self::assertSame([AdminLTEChartJSMinifyAsset::class], (new ChartJSWidgetAsset())->depends);
        self::assertContains('js/calendar.js', (new CalendarWidgetAsset())->js);
        self::assertContains('js/chartjs.js', (new ChartJSWidgetAsset())->js);
    }

    public function testAggregateKeepsHistoricalPluginOrderAndCoreIsSmaller(): void
    {
        $aggregate = $this->collectAdminlteFiles(AdminLTEAsset::class);
        $core = $this->collectAdminlteFiles(AdminLTECoreAsset::class);

        self::assertSame([
            'plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.css',
            'plugins/icheck-bootstrap/icheck-bootstrap.css',
            'dist/css/adminlte.css',
        ], $aggregate['css']);
        self::assertSame([
            'plugins/jquery-ui/jquery-ui.js',
            'plugins/moment/moment.min.js',
            'plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.js',
            'dist/js/adminlte.js',
        ], $aggregate['js']);

        self::assertSame(['dist/css/adminlte.css'], $core['css']);
        self::assertSame(['dist/js/adminlte.js'], $core['js']);
        self::assertLessThan(count($aggregate['css']) + count($aggregate['js']), count($core['css']) + count($core['js']));
    }

    public function parityProvider(): array
    {
        return [
            'aggregate' => [new AdminLTEAsset(), new AdminLTEMinifyAsset()],
            'core' => [new AdminLTECoreAsset(), new AdminLTECoreMinifyAsset()],
            'jquery-ui' => [new AdminLTEJqueryUiAsset(), new AdminLTEJqueryUiMinifyAsset()],
            'date-time' => [new AdminLTEDateTimeAsset(), new AdminLTEDateTimeMinifyAsset()],
            'icheck' => [new AdminLTEIcheckAsset(), new AdminLTEIcheckMinifyAsset()],
            'calendar' => [new AdminLTECalendarAsset(), new AdminLTECalendarMinifyAsset()],
            'chart-js' => [new AdminLTEChartJSAsset(), new AdminLTEChartJSMinifyAsset()],
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
            new AdminLTECalendarAsset(),
            new AdminLTECalendarMinifyAsset(),
            new AdminLTEChartJSAsset(),
            new AdminLTEChartJSMinifyAsset(),
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

    /**
     * Collects AdminLTE-owned files in dependency order, excluding Yii/vendor dependencies.
     *
     * @param class-string<AssetBundle> $class Bundle class.
     * @param array<class-string<AssetBundle>,bool> $visited Already traversed bundles.
     * @return array{css:string[],js:string[]}
     */
    private function collectAdminlteFiles(string $class, array &$visited = []): array
    {
        if (isset($visited[$class])) {
            return ['css' => [], 'js' => []];
        }
        $visited[$class] = true;

        /** @var AssetBundle $bundle */
        $bundle = new $class();
        $css = [];
        $js = [];
        foreach ($bundle->depends as $dependency) {
            if (strpos($dependency, 'cinghie\\adminlte3\\') !== 0) {
                continue;
            }
            $files = $this->collectAdminlteFiles($dependency, $visited);
            $css = array_merge($css, $files['css']);
            $js = array_merge($js, $files['js']);
        }

        return [
            'css' => array_merge($css, $bundle->css),
            'js' => array_merge($js, $bundle->js),
        ];
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
            AdminLTECalendarMinifyAsset::class => AdminLTECalendarAsset::class,
            AdminLTEChartJSMinifyAsset::class => AdminLTEChartJSAsset::class,
            FontAwesomeMinifyAsset::class => FontAwesomeAsset::class,
        ];

        return $map[$dependency] ?? $dependency;
    }
}
