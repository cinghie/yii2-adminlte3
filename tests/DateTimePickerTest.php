<?php

namespace cinghie\adminlte3\tests;

use PHPUnit\Framework\TestCase;

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
        foreach (['AdminLTEDateTimeAsset.php', 'AdminLTEDateTimeMinifyAsset.php'] as $file) {
            $src = file_get_contents(dirname(__DIR__) . '/' . $file);

            $this->assertStringContainsString('use yii\\bootstrap4\\BootstrapPluginAsset;', $src, $file);
            $this->assertStringContainsString('BootstrapPluginAsset::class', $src, $file);
            $this->assertStringNotContainsString('BootstrapAsset::class', $src, $file);
            $this->assertStringContainsString('tempusdominus-bootstrap-4', $src, $file);
        }
    }
}
