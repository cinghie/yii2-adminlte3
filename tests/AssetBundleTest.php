<?php

declare(strict_types=1);

namespace cinghie\adminlte3\tests;

use cinghie\adminlte3\AdminLTEAsset;
use cinghie\adminlte3\AdminLTEMinifyAsset;
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
}
