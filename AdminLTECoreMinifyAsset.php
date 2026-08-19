<?php

namespace cinghie\adminlte3;

use cinghie\adminlte3\assets\AdminLTEThemeAsset;
use cinghie\fontawesome\FontAwesomeMinifyAsset;
use yii\bootstrap4\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Minimal minified AdminLTE 3 asset bundle without optional plugin families.
 */
class AdminLTECoreMinifyAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/';
    public $appendTimestamp = true;

    public $css = [
        'dist/css/adminlte.min.css',
    ];

    public $js = [
        'dist/js/adminlte.min.js',
    ];

    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
        FontAwesomeMinifyAsset::class,
    ];

    public function registerAssetFiles($view)
    {
        parent::registerAssetFiles($view);
        AdminLTEThemeAsset::register($view);
    }
}
