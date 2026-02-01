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
 * SmallBox widget for AdminLTE 3.
 *
 * Renders a small-box: inner (h3 + p), icon, optional footer link.
 * All text is HTML-encoded; class/icon/bgClass are sanitized; link href is validated (no javascript:/data:).
 * Use inside a row: <div class="row"> ... SmallBox::widget() ... </div>
 *
 * @see https://adminlte.io/docs/3.0/components/boxes.html
 */
class SmallBox extends Widget
{
    /** bg-info (blue) */
    const COLOR_INFO = 'bg-info';

    /** bg-success (green) */
    const COLOR_SUCCESS = 'bg-success';

    /** bg-warning (yellow/orange) */
    const COLOR_WARNING = 'bg-warning';

    /** bg-danger (red) */
    const COLOR_DANGER = 'bg-danger';

    /** All 4 standard color classes (info, success, warning, danger) for iteration or dropdowns */
    const COLORS = [
        'info' => self::COLOR_INFO,
        'success' => self::COLOR_SUCCESS,
        'warning' => self::COLOR_WARNING,
        'danger' => self::COLOR_DANGER,
    ];

    /** @var string Wrapper column class (e.g. col-md-3 col-sm-6 col-12) */
    public $wrapperClass = 'col-md-3 col-sm-6 col-12';

    /** @var string Small-box background. Use SmallBox::COLOR_* constants or any valid AdminLTE class (e.g. bg-primary, bg-gradient-info). */
    public $bgClass = self::COLOR_INFO;

    /** @var string Main number/stat (h3) */
    public $title = '0';

    /** @var string Label below title (p) */
    public $subtitle = '';

    /** @var string Icon class (e.g. fas fa-shopping-cart). Font Awesome 5 by default. */
    public $icon = 'fas fa-shopping-cart';

    /** @var string|array|null Footer link URL. Null or empty = no footer link. Array = Url::to() */
    public $link;

    /** @var string Footer link text (e.g. "More info"). Ignored if $link is empty. */
    public $footerText = 'More info';

    /**
     * Sanitize string for use in class attribute (alphanumeric, space, hyphen only).
     * @param string|null $value
     * @param string $default
     * @return string
     */
    protected static function sanitizeClass($value, $default = '')
    {
        if ($value === null || $value === '') {
            return $default;
        }
        $sanitized = preg_replace('/[^\w\s\-]/', '', (string) $value);
        return $sanitized !== '' ? $sanitized : $default;
    }

    /**
     * Validate URL for href: reject javascript:, data:, vbscript:. Return '#' if unsafe.
     * @param string|array|null $url
     * @return string
     */
    protected static function safeLinkUrl($url)
    {
        if ($url === null || $url === '') {
            return '';
        }
        $href = is_array($url) ? Url::to($url) : (string) $url;
        if ($href === '' || $href === '#') {
            return $href;
        }
        if (preg_match('#^\s*javascript:#i', $href) || preg_match('#^\s*data:#i', $href) || preg_match('#^\s*vbscript:#i', $href)) {
            return '#';
        }
        return $href;
    }

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $wrapperClass = self::sanitizeClass($this->wrapperClass, 'col-md-3 col-sm-6 col-12');
        $bgClass = self::sanitizeClass($this->bgClass, 'bg-info');
        $iconClass = self::sanitizeClass($this->icon, 'fas fa-shopping-cart');

        $inner = Html::tag('div',
            Html::tag('h3', Html::encode($this->title)) . "\n" . Html::tag('p', Html::encode($this->subtitle)),
            ['class' => 'inner']);

        $icon = Html::tag('div', Html::tag('i', '', ['class' => $iconClass]), ['class' => 'icon']);

        $content = [$inner, $icon];

        if ($this->link !== null && $this->link !== '') {
            $href = self::safeLinkUrl($this->link);
            $footerContent = Html::encode($this->footerText) . ' ' . Html::tag('i', '', ['class' => 'fas fa-arrow-circle-right']);
            $content[] = Html::a($footerContent, $href ?: '#', ['class' => 'small-box-footer', 'encode' => false]);
        }

        $smallBox = Html::tag('div', implode("\n", $content), ['class' => 'small-box ' . $bgClass]);

        return Html::tag('div', $smallBox, ['class' => $wrapperClass]);
    }
}
