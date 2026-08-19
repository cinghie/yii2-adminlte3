<?php

namespace cinghie\adminlte3;

use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Optional minified AdminLTE-packaged jQuery UI assets.
 */
class AdminLTEJqueryUiMinifyAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/';
    public $appendTimestamp = true;

    public $js = [
        'plugins/jquery-ui/jquery-ui.min.js',
    ];

    public $depends = [
        YiiAsset::class,
    ];
}
