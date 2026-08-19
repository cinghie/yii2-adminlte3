<?php

namespace cinghie\adminlte3\widgets;

use yii\bootstrap4\Widget;
use yii\helpers\Html;

class InfoBox extends Widget
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
    public $iconBgClass = self::COLOR_INFO;
    public $text = '';
    public $number = '0';
    public $icon = 'fas fa-envelope';
    public $progress;
    public $progressDescription;

    protected static function sanitizeClass($value, $default = '')
    {
        if ($value === null || $value === '') {
            return $default;
        }
        $sanitized = preg_replace('/[^A-Za-z0-9_\- ]/', '', (string) $value);
        return $sanitized !== '' ? $sanitized : $default;
    }

    public function run()
    {
        $wrapperClass = self::sanitizeClass($this->wrapperClass, 'col-md-3 col-sm-6 col-12');
        $iconBgClass = self::sanitizeClass($this->iconBgClass, self::COLOR_INFO);
        $iconClass = self::sanitizeClass($this->icon, 'fas fa-envelope');

        $iconSpan = Html::tag('span', Html::tag('i', '', ['class' => $iconClass]), [
            'class' => 'info-box-icon ' . $iconBgClass,
        ]);

        $contentParts = [
            Html::tag('span', Html::encode($this->text), ['class' => 'info-box-text']),
            Html::tag('span', Html::encode($this->number), ['class' => 'info-box-number']),
        ];

        if ($this->progress !== null && $this->progress !== '') {
            $pct = max(0, min(100, (int) $this->progress));
            $contentParts[] = Html::tag('div', Html::tag('div', '', [
                'class' => 'progress-bar ' . $iconBgClass,
                'style' => 'width: ' . $pct . '%',
                'role' => 'progressbar',
                'aria-valuenow' => $pct,
                'aria-valuemin' => '0',
                'aria-valuemax' => '100',
            ]), ['class' => 'progress']);

            if ($this->progressDescription !== null && $this->progressDescription !== '') {
                $contentParts[] = Html::tag('span', Html::encode($this->progressDescription), [
                    'class' => 'progress-description',
                ]);
            }
        }

        $content = Html::tag('div', implode("\n", $contentParts), ['class' => 'info-box-content']);
        $infoBox = Html::tag('div', $iconSpan . "\n" . $content, ['class' => 'info-box']);
        return Html::tag('div', $infoBox, ['class' => $wrapperClass]);
    }
}
