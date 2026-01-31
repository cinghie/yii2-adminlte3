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
 * Simplebox1 widget for AdminLTE 3.
 * All text is HTML-encoded; class/icon/bgclass are sanitized (alphanumeric, space, hyphen, underscore only).
 */
class Simplebox1 extends Widget
{
    public $bgclass;
    public $class;
    public $icon;
    public $title;
    public $subtitle;

    /**
     * Sanitize string for use in class attribute (allow only safe CSS class chars).
     * @param string|null $value
     * @param string $default
     * @return string
     */
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
        if ($this->icon === null) {
            $this->icon = 'fa fa-envelope-o';
        }
        if ($this->title === null) {
            $this->title = 'Messages';
        }
        if ($this->subtitle === null) {
            $this->subtitle = '1,410';
        }
        parent::init();
    }

    public function run()
    {
        $class = self::sanitizeClass($this->class, 'col-md-3 col-sm-6 col-xs-12');
        $bgclass = self::sanitizeClass($this->bgclass, 'bg-aqua');
        $icon = self::sanitizeClass($this->icon, 'fa fa-envelope-o');
        return '<div class="'.Html::encode($class).'">
            <div class="info-box">
                <span class="info-box-icon '.Html::encode($bgclass).'">
                    <i class="'.Html::encode($icon).'"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">'.Html::encode($this->title).'</span>
                    <span class="info-box-number">'.Html::encode($this->subtitle).'</span>
                </div>
            </div>
        </div>';
    }
}
