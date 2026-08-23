<?php

namespace cinghie\adminlte3\tests;

use cinghie\adminlte3\assets\ColorPickerWidgetAsset;
use cinghie\adminlte3\widgets\ColorPicker;
use cinghie\adminlte3\widgets\DatePicker;
use PHPUnit\Framework\TestCase;
use Yii;
use yii\base\DynamicModel;

class ColorPickerTest extends TestCase
{
    public function testColorPickerUsesExternalAssetsAndSafeIconNormalization(): void
    {
        $src = file_get_contents(dirname(__DIR__) . '/widgets/ColorPicker.php');

        self::assertStringContainsString('ColorPickerWidgetAsset::register', $src);
        self::assertStringContainsString('SafeHtml::iconClass', $src);
        self::assertStringContainsString('DEFAULT_PALETTE', $src);
        self::assertStringContainsString("'data-cinghie-color-picker' => '1'", $src);
        self::assertStringNotContainsString('registerJs(', $src);
        self::assertStringNotContainsString('registerCss(', $src);
        self::assertStringNotContainsString("'style' =>", $src);
    }

    public function testColorPickerRendersModelBoundAndStandaloneInputsWithoutInlineStyle(): void
    {
        $view = Yii::$app->getView();
        $originalAssetBundles = $view->assetBundles;
        $originalJs = $view->js;
        $originalCss = $view->css;

        try {
            $view->assetBundles = [];
            $view->js = [];
            $view->css = [];

            $model = new DynamicModel(['color' => '#3c8dbc']);
            $html = ColorPicker::widget([
                'model' => $model,
                'attribute' => 'color',
                'label' => 'Theme color',
                'iconClass' => 'fas fa-paint-brush" onclick="bad',
            ]);

            self::assertStringContainsString('name="DynamicModel[color]"', $html);
            self::assertStringContainsString('value="#3c8dbc"', $html);
            self::assertStringContainsString('cinghie-color-popover', $html);
            self::assertStringContainsString('cinghie-color-swatch-chip', $html);
            self::assertStringContainsString('cinghie-color-preview', $html);
            self::assertStringContainsString('Theme color', $html);
            self::assertStringNotContainsString('style=', $html);
            self::assertStringNotContainsString('onclick=', $html);
            self::assertStringNotContainsString('<script', $html);
            self::assertStringNotContainsString('<style', $html);
            self::assertArrayHasKey(ColorPickerWidgetAsset::class, $view->assetBundles);
            self::assertSame([], $view->js);
            self::assertSame([], $view->css);

            $standalone = ColorPicker::widget([
                'name' => 'accent',
                'value' => '#00a65a',
            ]);
            self::assertStringContainsString('name="accent"', $standalone);
            self::assertStringContainsString('value="#00a65a"', $standalone);
        } finally {
            $view->assetBundles = $originalAssetBundles;
            $view->js = $originalJs;
            $view->css = $originalCss;
        }
    }

    public function testInvalidPaletteEntriesAreNotRendered(): void
    {
        $html = ColorPicker::widget([
            'name' => 'accent',
            'palette' => ['#00a65a', 'red', '#123', '#ffffff'],
        ]);

        self::assertStringContainsString('data-color="#00a65a"', $html);
        self::assertStringContainsString('data-color="#ffffff"', $html);
        self::assertStringNotContainsString('data-color="red"', $html);
        self::assertStringNotContainsString('data-color="#123"', $html);
    }

    public function testColorPickerInitializerAndStylesAreExternal(): void
    {
        $asset = new ColorPickerWidgetAsset();
        $script = file_get_contents(dirname(__DIR__) . '/assets/js/colorpicker.js');
        $css = file_get_contents(dirname(__DIR__) . '/assets/css/colorpicker.css');

        self::assertContains('js/colorpicker.js', $asset->js);
        self::assertContains('css/colorpicker.css', $asset->css);
        self::assertTrue($asset->appendTimestamp);
        self::assertIsString($script);
        self::assertStringContainsString('[data-cinghie-color-picker]', $script);
        self::assertStringContainsString('data-color', $script);
        self::assertIsString($css);
        self::assertStringContainsString('.cinghie-color-popover', $css);
    }

    public function testDatePickerUsesSharedTempusDominusDateTimePickerAndAllowsFormatOverride(): void
    {
        $src = file_get_contents(dirname(__DIR__) . '/widgets/DatePicker.php');
        self::assertStringContainsString('extends DateTimePicker', $src);
        self::assertStringContainsString("public \$format = 'YYYY-MM-DD'", $src);
        self::assertStringNotContainsString('function init()', $src);

        $model = new DynamicModel(['date' => '2026-08-23']);
        $html = DatePicker::widget([
            'model' => $model,
            'attribute' => 'date',
            'format' => 'DD/MM/YYYY',
        ]);

        self::assertStringContainsString('name="DynamicModel[date]"', $html);
        self::assertStringContainsString('datetimepicker-input', $html);
        self::assertStringContainsString('DD/MM/YYYY', html_entity_decode($html, ENT_QUOTES | ENT_HTML5));
    }
}
