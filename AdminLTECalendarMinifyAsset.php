<?php

namespace cinghie\adminlte3;

use yii\bootstrap4\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Optional minified FullCalendar assets bundled with AdminLTE 3.
 */
class AdminLTECalendarMinifyAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/';
    public $appendTimestamp = true;

    public $css = [
        'plugins/fullcalendar/main.min.css',
        'plugins/fullcalendar-daygrid/main.min.css',
        'plugins/fullcalendar-timegrid/main.min.css',
        'plugins/fullcalendar-bootstrap/main.min.css',
    ];

    public $js = [
        'plugins/fullcalendar/main.min.js',
        'plugins/fullcalendar-interaction/main.min.js',
        'plugins/fullcalendar-daygrid/main.min.js',
        'plugins/fullcalendar-timegrid/main.min.js',
        'plugins/fullcalendar-bootstrap/main.min.js',
    ];

    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
    ];
}
