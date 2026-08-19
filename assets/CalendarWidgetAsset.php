<?php

namespace cinghie\adminlte3\assets;

use cinghie\adminlte3\AdminLTECalendarMinifyAsset;
use yii\web\AssetBundle;

/**
 * Publishes the package-owned Calendar widget initializer.
 */
class CalendarWidgetAsset extends AssetBundle
{
    public $sourcePath = __DIR__;
    public $appendTimestamp = true;

    public $js = [
        'js/calendar.js',
    ];

    public $publishOptions = [
        'only' => [
            'js/calendar.js',
        ],
    ];

    public $depends = [
        AdminLTECalendarMinifyAsset::class,
    ];
}
