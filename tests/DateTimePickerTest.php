<?php

namespace cinghie\adminlte3\tests;

use PHPUnit\Framework\TestCase;

class DateTimePickerTest extends TestCase
{
    public function testWidgetUsesBundledTempusDominusWithPrependedIcon(): void
    {
        $src = file_get_contents(dirname(__DIR__) . '/widgets/DateTimePicker.php');

        $this->assertStringContainsString('AdminLTEDateTimeAsset::register', $src);
        $this->assertStringContainsString("'class' => 'input-group-prepend'", $src);
        $this->assertStringContainsString("'data-toggle' => 'datetimepicker'", $src);
        $this->assertStringContainsString("'data-target-input' => 'nearest'", $src);
        $this->assertStringContainsString("Json::encode('#' . \$wrapperId)", $src);
        $this->assertStringContainsString('datetimepicker({$config})', $src);
    }
}
