<?php

namespace cinghie\adminlte3\widgets;

use cinghie\adminlte3\widgets\support\SafeHtml;
use yii\bootstrap4\Widget;
use yii\helpers\Html;

/**
 * Renders an AdminLTE 3 information box with optional progress indicator.
 */
class InfoBox extends Widget
{
    public const COLOR_INFO = 'bg-info';
    public const COLOR_SUCCESS = 'bg-success';
    public const COLOR_WARNING = 'bg-warning';
    public const COLOR_DANGER = 'bg-danger';

    /** @var array<string,string> Legacy color aliases kept for compatibility. */
    public const COLORS = [
        'info' => self::COLOR_INFO,
        'success' => self::COLOR_SUCCESS,
        'warning' => self::COLOR_WARNING,
        'danger' => self::COLOR_DANGER,
    ];

    /** @var string Wrapper grid classes. */
    public $wrapperClass = 'col-md-3 col-sm-6 col-12';

    /** @var string Background class for the icon panel. */
    public $iconBgClass = self::COLOR_INFO;

    /** @var string Descriptive label. */
    public $text = '';

    /** @var string|int Numeric or textual value. */
    public $number = '0';

    /** @var string Font Awesome icon class list. */
    public $icon = 'fas fa-envelope';

    /** @var int|string|null Optional progress percentage. */
    public $progress;

    /** @var string|null Optional text shown below the progress bar. */
    public $progressDescription;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $wrapperClass = SafeHtml::cssClass($this->wrapperClass, 'col-md-3 col-sm-6 col-12');
        $iconBgClass = SafeHtml::cssClass($this->iconBgClass, self::COLOR_INFO);
        $iconClass = SafeHtml::iconClass($this->icon, 'fas fa-envelope');

        $iconSpan = Html::tag('span', Html::tag('i', '', ['class' => $iconClass]), [
            'class' => 'info-box-icon ' . $iconBgClass,
        ]);

        $contentParts = [
            Html::tag('span', Html::encode($this->text), ['class' => 'info-box-text']),
            Html::tag('span', Html::encode($this->number), ['class' => 'info-box-number']),
        ];

        if ($this->progress !== null && $this->progress !== '') {
            $pct = max(0, min(100, (int) $this->progress));
            $contentParts[] = Html::tag(
                'div',
                Html::tag('div', '', [
                    'class' => 'progress-bar ' . $iconBgClass,
                    'style' => 'width: ' . $pct . '%',
                    'role' => 'progressbar',
                    'aria-valuenow' => $pct,
                    'aria-valuemin' => '0',
                    'aria-valuemax' => '100',
                ]),
                ['class' => 'progress']
            );

            if ($this->progressDescription !== null && $this->progressDescription !== '') {
                $contentParts[] = Html::tag(
                    'span',
                    Html::encode($this->progressDescription),
                    ['class' => 'progress-description']
                );
            }
        }

        $content = Html::tag('div', implode("\n", $contentParts), ['class' => 'info-box-content']);
        $infoBox = Html::tag('div', $iconSpan . "\n" . $content, ['class' => 'info-box']);

        return Html::tag('div', $infoBox, ['class' => $wrapperClass]);
    }
}
