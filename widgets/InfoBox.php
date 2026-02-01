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
 * InfoBox widget for AdminLTE 3.
 *
 * Renders an info-box: icon (left), content (text + number), optional progress bar.
 * All text is HTML-encoded; class/icon/iconBgClass are sanitized.
 * Use inside a row: <div class="row"> ... InfoBox::widget() ... </div>
 *
 * @see https://adminlte.io/docs/3.0/components/boxes.html
 */
class InfoBox extends Widget
{
    /** bg-info (blue) */
    const COLOR_INFO = 'bg-info';

    /** bg-success (green) */
    const COLOR_SUCCESS = 'bg-success';

    /** bg-warning (yellow/orange) */
    const COLOR_WARNING = 'bg-warning';

    /** bg-danger (red) */
    const COLOR_DANGER = 'bg-danger';

    /** All 4 standard color classes for icon background */
    const COLORS = [
        'info' => self::COLOR_INFO,
        'success' => self::COLOR_SUCCESS,
        'warning' => self::COLOR_WARNING,
        'danger' => self::COLOR_DANGER,
    ];

    /** @var string Wrapper column class (e.g. col-md-3 col-sm-6 col-12) */
    public $wrapperClass = 'col-md-3 col-sm-6 col-12';

    /** @var string Icon container background: bg-info, bg-success, bg-warning, bg-danger, etc. */
    public $iconBgClass = self::COLOR_INFO;

    /** @var string Label text (info-box-text) */
    public $text = '';

    /** @var string Number/stat (info-box-number) */
    public $number = '0';

    /** @var string Icon class (e.g. fas fa-envelope). Font Awesome 5 by default. */
    public $icon = 'fas fa-envelope';

    /** @var int|null Progress bar value 0–100. Null = no progress bar. */
    public $progress;

    /** @var string|null Text below progress bar (e.g. "70% Increase in 30 Days"). Ignored if $progress is null. */
    public $progressDescription;

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

    public function init()
    {
        parent::init();
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
            $pct = (int) $this->progress;
            if ($pct < 0) {
                $pct = 0;
            }
            if ($pct > 100) {
                $pct = 100;
            }
            $progressBar = Html::tag('div', Html::tag('div', '', [
                'class' => 'progress-bar ' . $iconBgClass,
                'style' => 'width: ' . $pct . '%',
                'role' => 'progressbar',
                'aria-valuenow' => $pct,
                'aria-valuemin' => '0',
                'aria-valuemax' => '100',
            ]), ['class' => 'progress']);
            $contentParts[] = $progressBar;
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
