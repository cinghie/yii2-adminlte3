<?php

/**
 * @copyright Copyright &copy; Gogodigital Srls
 * @company Gogodigital Srls - Wide ICT Solutions
 * @website http://www.gogodigital.it
 * @github https://github.com/cinghie/yii2-adminlte3
 * @license BSD-3-Clause
 * @package yii2-adminlte3
 * @version 1.0.0
 */

namespace cinghie\adminlte3;

use cinghie\adminlte3\assets\AdminLTEThemeAsset;
use cinghie\fontawesome\FontAwesomeAsset;
use cinghie\ionicons\IoniconsAsset;
use yii\bootstrap4\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;
use yii\web\YiiAsset;

/**
 * AdminLTE 3 asset bundle (non-minified).
 */
class AdminLTEAsset extends AssetBundle
{
	/**
	 * @inheritdoc
	 */
	public $sourcePath = '@vendor/almasaeed2010/adminlte/';

	/**
	 * @inheritdoc
	 */
	public $css = [
		'plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.css',
		'plugins/icheck-bootstrap/icheck-bootstrap.css',
		'dist/css/adminlte.css',
	];

	/**
	 * @inheritdoc
	 */
	public $js = [
		'plugins/jquery-ui/jquery-ui.js',
		'plugins/bootstrap/js/bootstrap.bundle.js',
		'plugins/moment/moment.min.js',
		'plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.js',
		'dist/js/adminlte.js',
	];

	/**
	 * @inheritdoc
	 */
	public $depends = [
		YiiAsset::class,
		JqueryAsset::class,
		BootstrapAsset::class,
		FontAwesomeAsset::class,
		IoniconsAsset::class,
	];

	/**
	 * @inheritdoc
	 */
	public function registerAssetFiles($view)
	{
		parent::registerAssetFiles($view);
		// Theme overrides after core AdminLTE CSS (exportable with this package).
		AdminLTEThemeAsset::register($view);
	}
}
