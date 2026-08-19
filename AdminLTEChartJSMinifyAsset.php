<?php

namespace cinghie\adminlte3;

use yii\web\AssetBundle;

/**
 * Optional minified Chart.js assets bundled with AdminLTE 3.
 */
class AdminLTEChartJSMinifyAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/';
    public $appendTimestamp = true;

    public $css = [
        'plugins/chart.js/Chart.min.css',
    ];

    public $js = [
        'plugins/chart.js/Chart.min.js',
    ];

    public $depends = [];
}
