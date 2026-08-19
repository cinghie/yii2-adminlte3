<?php

namespace cinghie\adminlte3;

use yii\web\AssetBundle;

/**
 * Optional Chart.js assets bundled with AdminLTE 3.
 */
class AdminLTEChartJSAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/';
    public $appendTimestamp = true;

    public $css = [
        'plugins/chart.js/Chart.css',
    ];

    public $js = [
        'plugins/chart.js/Chart.js',
    ];

    public $depends = [];
}
