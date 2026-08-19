<?php

namespace cinghie\adminlte3;

use yii\bootstrap4\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Optional FullCalendar assets bundled with AdminLTE 3.
 */
class AdminLTECalendarAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/';
    public $appendTimestamp = true;

    public $css = [
        'plugins/fullcalendar/main.css',
        'plugins/fullcalendar-daygrid/main.css',
        'plugins/fullcalendar-timegrid/main.css',
        'plugins/fullcalendar-bootstrap/main.css',
    ];

    public $js = [
        'plugins/fullcalendar/main.js',
        'plugins/fullcalendar-interaction/main.js',
        'plugins/fullcalendar-daygrid/main.js',
        'plugins/fullcalendar-timegrid/main.js',
        'plugins/fullcalendar-bootstrap/main.js',
    ];

    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
    ];
}
