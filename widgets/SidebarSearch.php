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
 * Class SidebarSearch
 */
class SidebarSearch extends Widget
{
    /**
     * @var string
     */
	public $placeholder;

	/**
	 * @inheritdoc
	 */
    public function init()
    {
        if ($this->placeholder === null) {
            $this->placeholder = 'Search';
        }

        parent::init();
    }

	/**
	 * @return string
	 */
	public function run()
    {
        return '<form class="sidebar-form" method="get" action="#">
            <div class="input-group">
                <input type="text" placeholder="'.Html::encode($this->placeholder).'..." class="form-control" name="q">
              <span class="input-group-btn">
                <button class="btn btn-flat" id="search-btn" name="search" type="submit">
                    <i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>';
    }
}
