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
        'plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.css',
        'plugins/icheck-bootstrap/icheck-bootstrap.css',
        'dist/css/adminlte.css',
	];

	/**
	 * @inherit
	 */
	public $js = [
        'plugins/jquery/jquery.js',
        'plugins/jquery-ui/jquery-ui.js',
        'plugins/bootstrap/js/bootstrap.bundle.js',
        'plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.js',
        'dist/js/adminlte.js',
	];

	/**
     * @inherit
     */
	public $depends = [
		YiiAsset::class,
		BootstrapAsset::class,
		FontAwesomeAsset::class,
		IoniconsAsset::class
    ];
}
