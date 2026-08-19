<?php

namespace cinghie\adminlte3;

use cinghie\adminlte3\assets\AdminLTEThemeAsset;
use cinghie\fontawesome\FontAwesomeMinifyAsset;
use yii\bootstrap4\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * AdminLTE 3 minified asset bundle.
 */
class AdminLTEMinifyAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/';
    public $appendTimestamp = true;

    public $css = [
        'plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css',
        'plugins/icheck-bootstrap/icheck-bootstrap.min.css',
        'dist/css/adminlte.min.css',
    ];

    public $js = [
        'plugins/jquery-ui/jquery-ui.min.js',
        'plugins/moment/moment.min.js',
        'plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js',
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
