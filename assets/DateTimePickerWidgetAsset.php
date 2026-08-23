<?php

namespace cinghie\adminlte3\assets;

use yii\web\AssetBundle;

/**
 * Package-owned presentation and initializer for DateTimePicker/DatePicker.
 */
class DateTimePickerWidgetAsset extends AssetBundle
{
    public $sourcePath = '@cinghie/adminlte3/assets';

    public $css = [
        'css/datetimepicker.css',
    ];

    public $js = [
        'js/datetimepicker.js',
    ];

    public $appendTimestamp = true;
}
