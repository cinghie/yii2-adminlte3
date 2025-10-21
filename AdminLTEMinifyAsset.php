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
use yii\base\Exception;
use yii\bootstrap4\BootstrapAsset;
use yii\bootstrap4\BootstrapPluginAsset;
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
	    'dist/css/adminlte.min.css',
	];

	/**
     * @inherit
     */
	public $js = [
		'dist/js/adminlte.min.js',
	];

	/**
     * @inherit
     */
	public $depends = [
		YiiAsset::class,
        BootstrapAsset::class,
        BootstrapPluginAsset::class,
		FontAwesomeMinifyAsset::class,
		IoniconsMinifyAsset::class,
    ];
}
