<?php

namespace cinghie\adminlte3\assets;

use cinghie\adminlte3\AdminLTEChartJSMinifyAsset;
use yii\web\AssetBundle;

/**
 * Publishes the package-owned ChartJS widget presentation and initializer.
 */
class ChartJSWidgetAsset extends AssetBundle
{
    public $sourcePath = __DIR__;
    public $appendTimestamp = true;

    public $css = [
        'css/chartjs.css',
    ];

    public $js = [
        'js/chartjs.js',
    ];

    public $publishOptions = [
        'only' => [
            'css/chartjs.css',
            'js/chartjs.js',
        ],
    ];

    public $depends = [
        AdminLTEChartJSMinifyAsset::class,
    ];
}
