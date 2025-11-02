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
        return '<div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="'.$this->placeholder.'" aria-label="'.$this->placeholder.'">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>';
    }
}
