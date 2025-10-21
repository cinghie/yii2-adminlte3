<?php

/**
 * @copyright Copyright &copy; Gogodigital Srls
 * @company Gogodigital Srls - Wide ICT Solutions
 * @website http://www.gogodigital.it
 * @github https://github.com/cinghie/yii2-adminlte3
 * @license GNU GENERAL PUBLIC LICENSE VERSION 3
 * @package yii2-AdminLTE
 * @version 0.1.0
 */

namespace cinghie\adminlte3;

use cinghie\fontawesome\FontAwesomeMinifyAsset;
use cinghie\ionicons\IoniconsMinifyAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Class AdminLTEMinifyAsset
 */
class AdminLTEMinifyAsset extends AssetBundle
{
    /**
     * @inherit
     */
    public $sourcePath = '@vendor/almasaeed2010/adminlte/';

    /**
     * @inherit
     */
    public $css = [
        'plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css',
        'plugins/icheck-bootstrap/icheck-bootstrap.min.css',
        'dist/css/adminlte.min.css',
    ];

    /**
     * @inherit
     */
    public $js = [
        'plugins/jquery/jquery.min.js',
        'plugins/jquery-ui/jquery-ui.min.js',
        'plugins/bootstrap/js/bootstrap.bundle.min.js',
        'plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js',
        'dist/js/adminlte.min.js',
    ];

    /**
     * @inherit
     */
    public $depends = [
        YiiAsset::class,
        FontAwesomeMinifyAsset::class,
        IoniconsMinifyAsset::class,
    ];
}