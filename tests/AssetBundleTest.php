<?php

declare(strict_types=1);

namespace cinghie\adminlte3\tests;

use cinghie\adminlte3\AdminLTEAsset;
use cinghie\adminlte3\AdminLTEMinifyAsset;
use cinghie\adminlte3\assets\AdminLTEThemeAsset;
use PHPUnit\Framework\TestCase;
use yii\bootstrap4\BootstrapAsset;
use yii\web\JqueryAsset;
use yii\web\YiiAsset;

final class AssetBundleTest extends TestCase
{
    public function assetProvider(): array
    {
        return [
            [new AdminLTEAsset()],
            [new AdminLTEMinifyAsset()],
        ];
    }

    /**
     * @dataProvider assetProvider
     */
    public function testBootstrapAndJqueryAreNotDuplicated($asset): void
    {
        $this->assertContains(YiiAsset::class, $asset->depends);
        $this->assertContains(BootstrapAsset::class, $asset->depends);
        $this->assertNotContains(JqueryAsset::class, $asset->depends);
        $this->assertTrue($asset->appendTimestamp);

        foreach ($asset->js as $script) {
            $this->assertStringNotContainsString('plugins/bootstrap/js/bootstrap', $script);
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
}
