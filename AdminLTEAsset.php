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
use yii\base\Exception;
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
		FontAwesomeAsset::class,
		IoniconsAsset::class
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
