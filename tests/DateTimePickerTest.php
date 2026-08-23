<?php

namespace cinghie\adminlte3\tests;

use cinghie\adminlte3\AdminLTEDateTimeAsset;
use cinghie\adminlte3\AdminLTEDateTimeMinifyAsset;
use cinghie\adminlte3\assets\DateTimePickerWidgetAsset;
use cinghie\adminlte3\widgets\DateTimePicker;
use PHPUnit\Framework\TestCase;
use Yii;
use yii\base\DynamicModel;
use yii\bootstrap4\BootstrapPluginAsset;

class DateTimePickerTest extends TestCase
{
    public function testWidgetUsesBundledTempusDominusWithExternalInitializer(): void
    {
        $src = file_get_contents(dirname(__DIR__) . '/widgets/DateTimePicker.php');

        $this->assertStringContainsString('AdminLTEDateTimeAsset::class', $src);
        $this->assertStringContainsString('AdminLTEDateTimeMinifyAsset::class', $src);
        $this->assertStringContainsString('DateTimePickerWidgetAsset::register($view)', $src);
        $this->assertStringContainsString("'data-cinghie-datetimepicker' => '1'", $src);
        $this->assertStringContainsString('Json::encode($pluginOptions)', $src);
        $this->assertStringContainsString('SafeHtml::iconClass', $src);
        $this->assertStringNotContainsString('registerJs(', $src);
        $this->assertStringNotContainsString('registerCss(', $src);
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

    public function testRenderedWidgetRegistersAssetsAndEncodedOptionsWithoutInlineCode(): void
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
                'icon' => 'far fa-calendar-alt" onclick="bad',
                'toggleLabel' => 'Open schedule picker',
                'pluginOptions' => ['useCurrent' => false],
            ]);

            $this->assertStringContainsString('input-group-prepend', $html);
            $this->assertStringContainsString('data-cinghie-datetimepicker="1"', $html);
            $this->assertStringContainsString('data-cinghie-datetime-options=', $html);
            $this->assertStringContainsString('useCurrent', html_entity_decode($html, ENT_QUOTES | ENT_HTML5));
            $this->assertStringContainsString('aria-label="Open schedule picker"', $html);
            $this->assertStringNotContainsString('onclick=', $html);
            $this->assertStringNotContainsString('<script', $html);
            $this->assertStringNotContainsString('<style', $html);

            $expectedAsset = YII_DEBUG ? AdminLTEDateTimeAsset::class : AdminLTEDateTimeMinifyAsset::class;
            $this->assertArrayHasKey($expectedAsset, $view->assetBundles);
            $this->assertArrayHasKey(DateTimePickerWidgetAsset::class, $view->assetBundles);
            $this->assertSame([], $view->js);
            $this->assertSame([], $view->css);
        } finally {
            $view->assetBundles = $originalAssetBundles;
            $view->js = $originalJs;
            $view->css = $originalCss;
        }
    }

    public function testStandaloneWidgetUsesItsOwnIdWithoutModel(): void
    {
        $html = DateTimePicker::widget([
            'name' => 'starts_at',
            'value' => '2026-08-23 20:00:00',
        ]);

        $this->assertStringContainsString('name="starts_at"', $html);
        $this->assertStringContainsString('value="2026-08-23 20:00:00"', $html);
        $this->assertStringContainsString('data-cinghie-datetimepicker="1"', $html);
    }

    public function testWidgetInitializerIsExternalAndShared(): void
    {
        $asset = new DateTimePickerWidgetAsset();
        $script = file_get_contents(dirname(__DIR__) . '/assets/js/datetimepicker.js');
        $css = file_get_contents(dirname(__DIR__) . '/assets/css/datetimepicker.css');

        $this->assertContains('js/datetimepicker.js', $asset->js);
        $this->assertContains('css/datetimepicker.css', $asset->css);
        $this->assertTrue($asset->appendTimestamp);
        $this->assertIsString($script);
        $this->assertStringContainsString('[data-cinghie-datetimepicker]', $script);
        $this->assertStringContainsString("datetimepicker('show')", $script);
        $this->assertIsString($css);
        $this->assertStringContainsString('.bootstrap-datetimepicker-widget', $css);
    }

    public function testWidgetReusesAlreadyRegisteredSourceAssetInsteadOfAddingMinifiedVariant(): void
    {
        $view = Yii::$app->getView();
        $originalAssetBundles = $view->assetBundles;

        try {
            $view->assetBundles = [];
            AdminLTEDateTimeAsset::register($view);

            $model = new DynamicModel(['starts_at' => null]);
            DateTimePicker::widget(['model' => $model, 'attribute' => 'starts_at']);

            $this->assertArrayHasKey(AdminLTEDateTimeAsset::class, $view->assetBundles);
            $this->assertArrayNotHasKey(AdminLTEDateTimeMinifyAsset::class, $view->assetBundles);
            $this->assertArrayHasKey(DateTimePickerWidgetAsset::class, $view->assetBundles);
        } finally {
            $view->assetBundles = $originalAssetBundles;
        }
    }
}
