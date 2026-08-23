<?php

namespace cinghie\adminlte3;

use yii\bootstrap4\BootstrapPluginAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Optional minified AdminLTE date/time assets (Moment + Tempus Dominus Bootstrap 4).
 */
class AdminLTEDateTimeMinifyAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/';
    public $appendTimestamp = true;

    public $css = [
        'plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css',
    ];

    public $js = [
        'plugins/moment/moment.min.js',
        'plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js',
    ];

    public $depends = [
        YiiAsset::class,
        BootstrapPluginAsset::class,
    ];
}
