<?php

namespace cinghie\adminlte3\widgets;

use yii\bootstrap4\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class SmallBox extends Widget
{
    public const COLOR_INFO = 'bg-info';
    public const COLOR_SUCCESS = 'bg-success';
    public const COLOR_WARNING = 'bg-warning';
    public const COLOR_DANGER = 'bg-danger';
    public const COLORS = [
        'info' => self::COLOR_INFO,
        'success' => self::COLOR_SUCCESS,
        'warning' => self::COLOR_WARNING,
        'danger' => self::COLOR_DANGER,
    ];

    public $wrapperClass = 'col-md-3 col-sm-6 col-12';
    public $bgClass = self::COLOR_INFO;
    public $title = '0';
    public $subtitle = '';
    public $icon = 'fas fa-shopping-cart';
    public $link;
    public $footerText = 'More info';

    protected static function sanitizeClass($value, $default = '')
    {
        if ($value === null || $value === '') {
            return $default;
        }
        $sanitized = preg_replace('/[^A-Za-z0-9_\- ]/', '', (string) $value);
        return $sanitized !== '' ? $sanitized : $default;
    }

    protected static function safeLinkUrl($url)
    {
        if ($url === null || $url === '') {
            return '';
        }
        $href = is_array($url) ? Url::to($url) : (string) $url;
        if ($href === '' || $href === '#') {
            return $href;
        }
        if (preg_match('#^\s*(?:javascript|data|vbscript):#i', $href)) {
            return '#';
        }
        if (preg_match('#^[a-z][a-z0-9+.-]*:#i', $href) && !preg_match('#^https?://#i', $href)) {
            return '#';
        }
        return $href;
    }

    public function run()
    {
        $wrapperClass = self::sanitizeClass($this->wrapperClass, 'col-md-3 col-sm-6 col-12');
        $bgClass = self::sanitizeClass($this->bgClass, 'bg-info');
        $iconClass = self::sanitizeClass($this->icon, 'fas fa-shopping-cart');

        $inner = Html::tag(
            'div',
            Html::tag('h3', Html::encode($this->title)) . "\n" . Html::tag('p', Html::encode($this->subtitle)),
            ['class' => 'inner']
        );
        $icon = Html::tag('div', Html::tag('i', '', ['class' => $iconClass]), ['class' => 'icon']);
        $content = [$inner, $icon];

        if ($this->link !== null && $this->link !== '') {
            $footerContent = Html::encode($this->footerText) . ' ' . Html::tag('i', '', ['class' => 'fas fa-arrow-circle-right']);
            $content[] = Html::a($footerContent, self::safeLinkUrl($this->link) ?: '#', [
                'class' => 'small-box-footer',
            ]);
        }

        $smallBox = Html::tag('div', implode("\n", $content), ['class' => 'small-box ' . $bgClass]);
        return Html::tag('div', $smallBox, ['class' => $wrapperClass]);
    }
}
