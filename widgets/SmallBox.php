<?php

namespace cinghie\adminlte3\widgets;

use cinghie\adminlte3\widgets\support\SafeHtml;
use yii\bootstrap4\Widget;
use yii\helpers\Html;

/**
 * Renders an AdminLTE 3 small statistic box.
 */
class SmallBox extends Widget
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

    /** @var string Background utility class applied to the box. */
    public $bgClass = self::COLOR_INFO;

    /** @var string Main numeric/text value. */
    public $title = '0';

    /** @var string Secondary descriptive text. */
    public $subtitle = '';

    /** @var string Font Awesome icon class list. */
    public $icon = 'fas fa-shopping-cart';

    /** @var string|array|null Optional footer link target. */
    public $link;

    /** @var string Footer link label. */
    public $footerText = 'More info';

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $wrapperClass = SafeHtml::cssClass($this->wrapperClass, 'col-md-3 col-sm-6 col-12');
        $bgClass = SafeHtml::cssClass($this->bgClass, self::COLOR_INFO);
        $iconClass = SafeHtml::iconClass($this->icon, 'fas fa-shopping-cart');

        $inner = Html::tag(
            'div',
            Html::tag('h3', Html::encode($this->title)) . "\n" . Html::tag('p', Html::encode($this->subtitle)),
            ['class' => 'inner']
        );
        $icon = Html::tag('div', Html::tag('i', '', ['class' => $iconClass]), ['class' => 'icon']);
        $content = [$inner, $icon];

        if ($this->link !== null && $this->link !== '') {
            $footerContent = Html::encode($this->footerText)
                . ' '
                . Html::tag('i', '', ['class' => 'fas fa-arrow-circle-right']);
            $content[] = Html::a(
                $footerContent,
                SafeHtml::linkUrl($this->link, '#'),
                ['class' => 'small-box-footer']
            );
        }

        $smallBox = Html::tag('div', implode("\n", $content), ['class' => 'small-box ' . $bgClass]);

        return Html::tag('div', $smallBox, ['class' => $wrapperClass]);
    }
}
