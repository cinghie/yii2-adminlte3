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
		'plugins/bootstrap/js/bootstrap.bundle.min.js',
		'dist/js/adminlte.min.js',
	];
	
	/**
     * @inherit
     */
	public $depends = [
		YiiAsset::class,
		FontAwesomeMinifyAsset::class,
		IoniconsMinifyAsset::class
    ];

	/**
	 * @var string|bool Choose skin color, eg. `'skin-blue'` or set `false` to disable skin loading
	 *
	 * @see https://almsaeedstudio.com/themes/AdminLTE/documentation/index.html#layout
	 */
	public $skin = '_all-skins';

	/**
	 * Append skin color file if specified
	 *
	 * @throws Exception
	 */
	public function init()
	{
		if ($this->skin) {
			if (('_all-skins' !== $this->skin) && (!str_starts_with($this->skin, 'skin-'))) {
				throw new Exception('Invalid skin specified');
			}
			$this->css[] = sprintf('dist/css/skins/%s.min.css', $this->skin);
		}

		parent::init();
	}
}
