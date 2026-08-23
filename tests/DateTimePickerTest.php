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
        $this->assertStringContainsString('isset($view->assetBundles[AdminLTEDateTimeAsset::class])', $src);
        $this->assertStringContainsString('isset($view->assetBundles[AdminLTEDateTimeMinifyAsset::class])', $src);
        $this->assertStringContainsString('YII_DEBUG ?', $src);
        $this->assertStringContainsString("'class' => 'input-group-prepend'", $src);
        $this->assertStringContainsString("'data-toggle' => 'datetimepicker'", $src);
        $this->assertStringContainsString("'data-cinghie-datetime-toggle' => '1'", $src);
        $this->assertStringContainsString("'data-target-input' => 'nearest'", $src);
        $this->assertStringContainsString("'allowInputToggle' => true", $src);
        $this->assertStringContainsString("picker.datetimepicker('show')", $src);
        $this->assertStringContainsString("focus.cinghieDateTimePicker", $src);
        $this->assertStringContainsString("Json::encode('#' . \$wrapperId)", $src);
        $this->assertStringContainsString('bootstrap-datetimepicker-widget{z-index:1080!important}', $src);
        $this->assertStringContainsString("public \$toggleLabel = 'Open date and time picker'", $src);
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

    public function testRenderedWidgetRegistersAssetAndExplicitInitializer(): void
    {
        $view = Yii::$app->getView();
        $originalAssetBundles = $view->assetBundles;
        $originalJs = $view->js;
        $originalCss = $view->css;

        try {
            $view->assetBundles = [];
            $view->js = [];
            $view->css = [];

            $model = new DynamicModel(['starts_at' => '2026-08-23 19:30:00']);
            $html = DateTimePicker::widget([
                'model' => $model,
                'attribute' => 'starts_at',
                'icon' => 'far fa-calendar-alt',
                'toggleLabel' => 'Open schedule picker',
                'pluginOptions' => ['useCurrent' => false],
            ]);

            $this->assertStringContainsString('input-group-prepend', $html);
            $this->assertStringContainsString('data-toggle="datetimepicker"', $html);
            $this->assertStringContainsString('data-cinghie-datetime-toggle="1"', $html);
            $this->assertStringContainsString('datetimepicker-input', $html);
            $this->assertStringContainsString('far fa-calendar-alt', $html);
            $this->assertStringContainsString('aria-label="Open schedule picker"', $html);

            $expectedAsset = YII_DEBUG ? AdminLTEDateTimeAsset::class : AdminLTEDateTimeMinifyAsset::class;
            $this->assertArrayHasKey($expectedAsset, $view->assetBundles);

            $js = json_encode($view->js);
            $this->assertStringContainsString('.datetimepicker(', $js);
            $this->assertStringContainsString("datetimepicker('show')", $js);
            $this->assertStringContainsString('allowInputToggle', $js);
            $this->assertStringContainsString('useCurrent', $js);
            $this->assertStringContainsString('cinghie-datetimepicker-ready', $js);

            $css = json_encode($view->css);
            $this->assertStringContainsString('bootstrap-datetimepicker-widget', $css);
            $this->assertStringContainsString('z-index:1080', $css);
        } finally {
            $view->assetBundles = $originalAssetBundles;
            $view->js = $originalJs;
            $view->css = $originalCss;
        }
    }

    public function testWidgetReusesAlreadyRegisteredSourceAssetInsteadOfAddingMinifiedVariant(): void
    {
        $view = Yii::$app->getView();
        $originalAssetBundles = $view->assetBundles;
        $originalJs = $view->js;
        $originalCss = $view->css;

        try {
            $view->assetBundles = [];
            $view->js = [];
            $view->css = [];
            AdminLTEDateTimeAsset::register($view);

            $model = new DynamicModel(['starts_at' => null]);
            DateTimePicker::widget(['model' => $model, 'attribute' => 'starts_at']);

            $this->assertArrayHasKey(AdminLTEDateTimeAsset::class, $view->assetBundles);
            $this->assertArrayNotHasKey(AdminLTEDateTimeMinifyAsset::class, $view->assetBundles);
        } finally {
            $view->assetBundles = $originalAssetBundles;
            $view->js = $originalJs;
            $view->css = $originalCss;
        }
    }
}
