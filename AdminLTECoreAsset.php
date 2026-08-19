<?php

namespace cinghie\adminlte3;

use cinghie\adminlte3\assets\AdminLTEThemeAsset;
use cinghie\fontawesome\FontAwesomeAsset;
use yii\bootstrap4\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Minimal AdminLTE 3 asset bundle without optional plugin families.
 *
 * Register this bundle on pages that only need the AdminLTE shell. Optional
 * plugins such as jQuery UI, Tempus Dominus and iCheck can be registered
 * separately through their dedicated bundles.
 */
class AdminLTECoreAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/';
    public $appendTimestamp = true;

    public $css = [
        'dist/css/adminlte.css',
    ];

    public $js = [
        'dist/js/adminlte.js',
    ];

    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
        FontAwesomeAsset::class,
    ];

    public function registerAssetFiles($view)
    {
        parent::registerAssetFiles($view);
        AdminLTEThemeAsset::register($view);
    }
}
