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
use yii\helpers\Html;

/**
 * SidebarToggle widget for AdminLTE 3 with Bootstrap 4.
 *
 * Renders the pushmenu button used in the navbar to collapse/expand the sidebar.
 * When the sidebar is collapsed (sidebar-mini), this button toggles the "sidebar-collapse"
 * class on body. Works in both expanded and collapsed sidebar states.
 *
 * Use inside navbar: <ul class="navbar-nav"><?= SidebarToggle::widget() ?></ul>
 *
 * @see https://adminlte.io/docs/3.1/components/main-sidebar.html
 */
class SidebarToggle extends Widget
{
    /**
     * @var string Icon class for the toggle button (Font Awesome 5)
     */
    public $iconClass = 'fas fa-bars';

    /**
     * @var string Aria-label for accessibility
     */
    public $ariaLabel;

    /**
     * @var bool Whether to wrap the output in <li class="nav-item"> (for use inside navbar-nav)
     */
    public $renderAsLi = true;

    /**
     * @var array HTML attributes for the anchor tag
     */
    public $linkOptions = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->ariaLabel === null) {
            $this->ariaLabel = Yii::t('app', 'Toggle sidebar');
        }
        parent::init();
    }

    /**
     * @return string
     */
    public function run()
    {
        $linkOptions = array_merge([
            'class' => 'nav-link',
            'data-widget' => 'pushmenu',
            'href' => '#',
            'role' => 'button',
            'aria-label' => $this->ariaLabel,
        ], $this->linkOptions);

        $icon = Html::tag('i', '', ['class' => $this->iconClass]);
        $link = Html::a($icon, '#', $linkOptions);

        if ($this->renderAsLi) {
            return Html::tag('li', $link, ['class' => 'nav-item']);
        }

        return $link;
    }
}
