<?php

namespace cinghie\adminlte3;

use cinghie\adminlte3\assets\AdminLTEThemeAsset;
use cinghie\fontawesome\FontAwesomeAsset;
use yii\bootstrap4\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * AdminLTE 3 asset bundle (non-minified).
 */
class AdminLTEAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/';
    public $appendTimestamp = true;

    public $css = [
        'plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.css',
        'plugins/icheck-bootstrap/icheck-bootstrap.css',
        'dist/css/adminlte.css',
    ];

    public $js = [
        'plugins/jquery-ui/jquery-ui.js',
        'plugins/moment/moment.min.js',
        'plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.js',
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
