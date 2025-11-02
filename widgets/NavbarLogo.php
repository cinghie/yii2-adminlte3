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

namespace cinghie\adminlte3\widgets;

use Yii;
use yii\bootstrap4\Widget;

/**
 * Class NavbarLogo
 */
class NavbarLogo extends Widget
{
    /**
     * @var string
     */
    public $logo_url;

	/**
	 * @return string
	 */
	public function run()
    {
        $html = '<a href="'.Yii::$app->homeUrl.'" class="brand-link text-center">';

        if($this->logo_url) {
            $html .= '<img src="'.$this->logo_url.'" alt="'.Yii::$app->name.' Logo" class="brand-image img-circle elevation-3" style="opacity: .8">';
        }

        $html .= '<span class="brand-text font-weight-light">';
        $html .= Yii::$app->name;
        $html .= '</span></a>';

        return $html;
    }
}
