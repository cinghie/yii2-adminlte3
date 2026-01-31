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

/**
 * Simplebox3 widget for AdminLTE 3.
 * All text is HTML-encoded; class/icon/bgclass are sanitized; link href is encoded for attribute context.
 */
class Simplebox3 extends Widget
{
    public $bgclass;
    public $class;
    public $description;
    public $icon;
    public $link;
    public $title;
    public $subtitle;

    protected static function sanitizeClass($value, $default = '')
    {
        if ($value === null || $value === '') {
            return $default;
        }
        $sanitized = preg_replace('/[^\w\s\-]/', '', $value);
        return $sanitized !== '' ? $sanitized : $default;
    }

    public function init()
    {
        if ($this->bgclass === null) {
            $this->bgclass = 'bg-aqua';
        }
        if ($this->class === null) {
            $this->class = 'col-md-3 col-sm-6 col-xs-12';
        }
        if ($this->description === null) {
            $this->description = 'More info';
        }
        if ($this->icon === null) {
            $this->icon = 'fa fa-shopping-cart';
        }
        if ($this->link === null) {
            $this->link = '#';
        }
        if ($this->title === null) {
            $this->title = '150';
        }
        if ($this->subtitle === null) {
            $this->subtitle = 'New Orders';
        }
        parent::init();
    }

    public function run()
    {
        $class = self::sanitizeClass($this->class, 'col-md-3 col-sm-6 col-xs-12');
        $bgclass = self::sanitizeClass($this->bgclass, 'bg-aqua');
        $icon = self::sanitizeClass($this->icon, 'fa fa-shopping-cart');
        $linkHref = Html::encode($this->link);
        return '<div class="'.Html::encode($class).'">
            <div class="small-box '.Html::encode($bgclass).'">
                <div class="inner">
                    <h3>'.Html::encode($this->title).'</h3>
                    <p>'.Html::encode($this->subtitle).'</p>
                </div>
                <div class="icon">
                    <i class="'.Html::encode($icon).'"></i>
                </div>
                <a class="small-box-footer" href="'.$linkHref.'">
                    '.Html::encode($this->description).' <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>';
    }
}
