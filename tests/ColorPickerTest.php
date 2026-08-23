<?php

namespace cinghie\adminlte3\tests;

use cinghie\adminlte3\widgets\ColorPicker;
use cinghie\adminlte3\widgets\DatePicker;
use PHPUnit\Framework\TestCase;
use Yii;
use yii\base\DynamicModel;

class ColorPickerTest extends TestCase
{
    public function testColorPickerIsDeferredResponsiveAndGeneric(): void
    {
        $src = file_get_contents(dirname(__DIR__) . '/widgets/ColorPicker.php');

        self::assertStringContainsString("'hidden' => true", $src);
        self::assertStringContainsString('cinghie-color-popover', $src);
        self::assertStringContainsString('cinghie-color-toggle', $src);
        self::assertStringContainsString('DEFAULT_PALETTE', $src);
        self::assertStringContainsString('@media(max-width:991px)', $src);
        self::assertStringContainsString("public \$iconClass = 'fas fa-paint-brush'", $src);
        self::assertStringContainsString('$this->hasModel()', $src);
        self::assertStringContainsString('Html::textInput($this->name, $this->value', $src);
        self::assertStringNotContainsString("Yii::t('events'", $src);
    }

    public function testColorPickerRendersModelBoundAndStandaloneInputs(): void
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
            ]);

            self::assertStringContainsString('name="DynamicModel[color]"', $html);
            self::assertStringContainsString('value="#3c8dbc"', $html);
            self::assertStringContainsString('cinghie-color-popover', $html);
            self::assertStringContainsString('hidden', $html);
            self::assertStringContainsString('Theme color', $html);

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

    public function testDatePickerUsesSharedTempusDominusDateTimePickerAndAllowsFormatOverride(): void
    {
        $src = file_get_contents(dirname(__DIR__) . '/widgets/DatePicker.php');
        self::assertStringContainsString('extends DateTimePicker', $src);
        self::assertStringContainsString("public \$format = 'YYYY-MM-DD'", $src);
        self::assertStringNotContainsString('function init()', $src);

        $view = Yii::$app->getView();
        $originalAssetBundles = $view->assetBundles;
        $originalJs = $view->js;
        $originalCss = $view->css;

        try {
            $view->assetBundles = [];
            $view->js = [];
            $view->css = [];

            $model = new DynamicModel(['date' => '2026-08-23']);
            $html = DatePicker::widget([
                'model' => $model,
                'attribute' => 'date',
                'format' => 'DD/MM/YYYY',
            ]);

            self::assertStringContainsString('name="DynamicModel[date]"', $html);
            self::assertStringContainsString('datetimepicker-input', $html);
            $js = json_encode($view->js);
            self::assertStringContainsString('DD/MM/YYYY', $js);
        } finally {
            $view->assetBundles = $originalAssetBundles;
            $view->js = $originalJs;
            $view->css = $originalCss;
        }
    }
}
