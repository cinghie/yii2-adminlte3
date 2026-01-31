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

use yii\bootstrap4\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * NavbarButton widget for AdminLTE 3 with Bootstrap 4.
 *
 * Renders a single navbar link (nav-item > nav-link).
 * Use inside: <ul class="navbar-nav"> ... </ul>
 *
 * @see https://adminlte.io/docs/3.1/components/navbar.html
 */
class NavbarButton extends Widget
{
    /**
     * @var string Link content (HTML allowed, e.g. icon + text). Default: icon only.
     */
    public $title;

    /**
     * @var string|array Link URL (array will be passed to Url::to())
     */
    public $url = '#';

    /**
     * @var string|null Target attribute (e.g. '_blank'). Null = not set.
     */
    public $target;

    /**
     * @var array HTML options for the anchor (class => 'nav-link' is merged by default)
     */
    public $option = [];

    /**
     * @var bool Whether to wrap in <li class="nav-item">
     */
    public $renderAsLi = true;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->title === null) {
            $this->title = '<i class="fas fa-external-link-alt"></i>';
        }
        if ($this->option === null) {
            $this->option = [];
        }
        parent::init();
    }

    /**
     * @return string
     */
    public function run()
    {
        $url = is_array($this->url) ? Url::to($this->url) : $this->url;

        $options = array_merge(['class' => 'nav-link'], $this->option);
        if ($this->target !== null && $this->target !== '') {
            $options['target'] = $this->target;
            if ($this->target === '_blank') {
                $options['rel'] = 'noopener noreferrer';
            }
        }

        $link = Html::a($this->title, $url, $options);

        if ($this->renderAsLi) {
            return Html::tag('li', $link, ['class' => 'nav-item']);
        }

        return $link;
    }
}
