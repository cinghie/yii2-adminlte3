<?php

namespace cinghie\adminlte3\assets;

use cinghie\adminlte3\AdminLTEChartJSMinifyAsset;
use yii\web\AssetBundle;

/**
 * Publishes the package-owned ChartJS widget initializer.
 */
class ChartJSWidgetAsset extends AssetBundle
{
    public $sourcePath = __DIR__;
    public $appendTimestamp = true;

    public $js = [
        'js/chartjs.js',
    ];

    public $publishOptions = [
        'only' => [
            'js/chartjs.js',
        ],
    ];

    public $depends = [
        AdminLTEChartJSMinifyAsset::class,
        AdminLTEThemeAsset::class,
    ];
}
