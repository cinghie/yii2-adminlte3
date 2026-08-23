<?php

namespace cinghie\adminlte3\assets;

use yii\web\AssetBundle;

/**
 * Package-owned presentation and initializer for DateTimePicker/DatePicker.
 */
class DateTimePickerWidgetAsset extends AssetBundle
{
    public $sourcePath = __DIR__;

    public $css = [
        'css/datetimepicker.css',
    ];

    public $js = [
        'js/datetimepicker.js',
    ];

    public $appendTimestamp = true;

    public $publishOptions = [
        'only' => [
            'css/datetimepicker.css',
            'js/datetimepicker.js',
        ],
    ];
}
