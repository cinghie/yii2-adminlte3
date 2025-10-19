<?php

/**
* @copyright Copyright &copy; Gogodigital Srls
* @company Gogodigital Srls - Wide ICT Solutions 
* @website http://www.gogodigital.it
* @github https://github.com/cinghie/yii2-adminlte3
* @license MIT
* @package yii2-adminlte3
* @version 1.0.0
*/

namespace cinghie\adminlte3;

use cinghie\fontawesome\FontAwesomeAsset;
use cinghie\ionicons\IoniconsAsset;
use yii\bootstrap4\BootstrapAsset;
use yii\bootstrap4\BootstrapPluginAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Class AdminLTEAsset
 */
class AdminLTEAsset extends AssetBundle
{
	/**
	 * @inherit
	 */
	public $sourcePath = '@vendor/almasaeed2010/adminlte/';

	/**
	 * @inherit
	 */
	public $css = [
		'dist/css/adminlte.css'
	];

	/**
	 * @inherit
	 */
	public $js = [
		'dist/js/adminlte.js',
	];

	/**
     * @inherit
     */
	public $depends = [
		YiiAsset::class,
        BootstrapAsset::class,
        BootstrapPluginAsset::class,
		FontAwesomeAsset::class,
		IoniconsAsset::class
    ];
}
