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

namespace cinghie\adminlte3\assets;

use yii\web\AssetBundle;

/**
 * Package theme / layout overrides for AdminLTE 3.
 * Registered automatically after {@see \cinghie\adminlte3\AdminLTEAsset} / {@see \cinghie\adminlte3\AdminLTEMinifyAsset}.
 *
 * Static files live under this same `assets/` directory (`css/…`); only CSS is published.
 */
class AdminLTEThemeAsset extends AssetBundle
{
	/**
	 * @inheritdoc
	 */
	public $sourcePath = __DIR__;

	/**
	 * Bust browser cache when theme CSS changes (publish hash alone is path-based).
	 *
	 * @inheritdoc
	 */
	public $appendTimestamp = true;

	/**
	 * @inheritdoc
	 */
	public $css = [
		'css/adminlte-theme.css',
	];

	/**
	 * Do not publish PHP AssetBundle classes alongside CSS.
	 *
	 * @inheritdoc
	 */
	public $publishOptions = [
		'only' => [
			'css/*',
		],
	];

	/**
	 * @inheritdoc
	 */
	public $depends = [];

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
		// In debug, re-copy CSS when source changes (Yii publish hash is path-based).
		if (defined('YII_DEBUG') && YII_DEBUG) {
			$this->publishOptions['forceCopy'] = true;
		}
	}
}
