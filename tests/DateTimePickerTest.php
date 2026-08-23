<?php

namespace cinghie\adminlte3\tests;

use Yii;
use cinghie\adminlte3\AdminLTEDateTimeAsset;
use cinghie\adminlte3\AdminLTEDateTimeMinifyAsset;
use cinghie\adminlte3\widgets\DateTimePicker;
use PHPUnit\Framework\TestCase;
use yii\base\DynamicModel;
use yii\bootstrap4\BootstrapPluginAsset;

class DateTimePickerTest extends TestCase
{
    public function testWidgetUsesBundledTempusDominusWithPrependedIcon(): void
    {
        $src = file_get_contents(dirname(__DIR__) . '/widgets/DateTimePicker.php');

        $this->assertStringContainsString('AdminLTEDateTimeAsset::class', $src);
        $this->assertStringContainsString('AdminLTEDateTimeMinifyAsset::class', $src);
        $this->assertStringContainsString('YII_DEBUG ?', $src);
        $this->assertStringContainsString("'class' => 'input-group-prepend'", $src);
        $this->assertStringContainsString("'data-toggle' => 'datetimepicker'", $src);
        $this->assertStringContainsString("'data-target-input' => 'nearest'", $src);
        $this->assertStringContainsString("Json::encode('#' . \$wrapperId)", $src);
        $this->assertStringContainsString('datetimepicker({$config})', $src);
    }

    public function testDateTimeAssetsLoadBootstrapJavascriptBeforeTempusDominus(): void
    {
        foreach ([new AdminLTEDateTimeAsset(), new AdminLTEDateTimeMinifyAsset()] as $asset) {
            $this->assertContains(BootstrapPluginAsset::class, $asset->depends);
            $this->assertNotEmpty($asset->js);
            $this->assertStringContainsString('moment', $asset->js[0]);
            $this->assertStringContainsString('tempusdominus-bootstrap-4', $asset->js[1]);
        }
    }

    public function testRenderedWidgetRegistersAssetAndInitializer(): void
    {
        $view = Yii::$app->getView();
        $originalAssetBundles = $view->assetBundles;
        $originalJs = $view->js;

        try {
            $view->assetBundles = [];
            $view->js = [];

            $model = new DynamicModel(['starts_at' => '2026-08-23 19:30:00']);
            $html = DateTimePicker::widget([
                'model' => $model,
                'attribute' => 'starts_at',
                'icon' => 'far fa-calendar-alt',
                'pluginOptions' => ['useCurrent' => false],
            ]);

            $this->assertStringContainsString('input-group-prepend', $html);
            $this->assertStringContainsString('data-toggle="datetimepicker"', $html);
            $this->assertStringContainsString('datetimepicker-input', $html);
            $this->assertStringContainsString('far fa-calendar-alt', $html);

            $expectedAsset = YII_DEBUG ? AdminLTEDateTimeAsset::class : AdminLTEDateTimeMinifyAsset::class;
            $this->assertArrayHasKey($expectedAsset, $view->assetBundles);
            $this->assertStringContainsString('.datetimepicker(', json_encode($view->js));
            $this->assertStringContainsString('useCurrent', json_encode($view->js));
        } finally {
            $view->assetBundles = $originalAssetBundles;
            $view->js = $originalJs;
        }
    }
}
