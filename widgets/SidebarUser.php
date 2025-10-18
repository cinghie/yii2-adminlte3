<?php

/**
* @copyright Copyright &copy; Gogodigital Srls
* @company Gogodigital Srls - Wide ICT Solutions 
* @website http://www.gogodigital.it
* @github https://github.com/cinghie/yii2-admin-lte
* @license GNU GENERAL PUBLIC LICENSE VERSION 3
* @package yii2-AdminLTE
* @version 1.5.5
*/

namespace cinghie\adminlte3\widgets;

use yii\bootstrap\Widget;
use yii\helpers\Html;

/**
 * Class SidebarUser
 */
class SidebarUser extends Widget
{
    /**
     * @var string
     */
	public $username;

    /**
     * @var string
     */
	public $userimg;

	/**
	 * @inheritdoc
	 */
	public function init()
    {
        if ($this->username === null) {
            $this->username = 'Your Username';
        }
		
		if ($this->userimg === null) {
            $this->userimg = 'https://cdn0.iconfinder.com/data/icons/user-pictures/100/matureman1-2-128.png';
        }

        parent::init();
    }

	/**
	 * @return string
	 */
	public function run()
    {
        return '<div class="user-panel">
            <div class="pull-left image">
                <img alt="User Image" class="img-circle" src="'.$this->userimg.'">
            </div>
            <div class="pull-left info">
                <p>'.Html::encode($this->username).'</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>';
    }
}
