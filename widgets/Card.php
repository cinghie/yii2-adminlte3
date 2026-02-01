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
 * Card widget for AdminLTE 3.
 *
 * Renders a simple card: optional header (title + icon), body, optional footer.
 * All text is HTML-encoded by default; class/icon are sanitized.
 * Use inside a row when using wrapperClass: <div class="row"> ... Card::widget() ... </div>
 *
 * @see https://adminlte.io/docs/3.0/components/cards.html
 */
class Card extends Widget
{
    /** card-info (blue) */
    const COLOR_INFO = 'card-info';

    /** card-success (green) */
    const COLOR_SUCCESS = 'card-success';

    /** card-warning (yellow/orange) */
    const COLOR_WARNING = 'card-warning';

    /** card-danger (red) */
    const COLOR_DANGER = 'card-danger';

    /** All 4 standard card type classes */
    const COLORS = [
        'info' => self::COLOR_INFO,
        'success' => self::COLOR_SUCCESS,
        'warning' => self::COLOR_WARNING,
        'danger' => self::COLOR_DANGER,
    ];

    /** @var string|null Wrapper column class (e.g. col-md-6). Null = no wrapper div. */
    public $wrapperClass;

    /** @var string|null Card type: card-info, card-success, card-warning, card-danger, card-primary, card-outline card-info, etc. */
    public $cardClass;

    /** @var string|null Header title. Null or empty = no header. */
    public $title;

    /** @var string|null Header icon class (e.g. fas fa-chart-pie). Null = no icon. */
    public $titleIcon;

    /** @var string Body content. */
    public $body = '';

    /** @var string|null Footer content. Null or empty = no footer. */
    public $footer;

    /** @var bool Whether to HTML-encode body. Default true to prevent XSS. */
    public $encodeBody = true;

    /** @var bool Whether to HTML-encode title. Default true. */
    public $encodeTitle = true;

    /** @var bool Whether to HTML-encode footer. Default true. */
    public $encodeFooter = true;

    /** @var array HTML options for the card container */
    public $options = [];

    /** @var array HTML options for the card body */
    public $bodyOptions = [];

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
        $cardClass = 'card';
        if ($this->cardClass !== null && $this->cardClass !== '') {
            $cardClass .= ' ' . self::sanitizeClass($this->cardClass, '');
        }

        $parts = [];

        if ($this->title !== null && $this->title !== '') {
            $titleContent = $this->encodeTitle ? Html::encode($this->title) : $this->title;
            if ($this->titleIcon !== null && $this->titleIcon !== '') {
                $iconClass = self::sanitizeClass($this->titleIcon, '');
                $titleContent = Html::tag('i', '', ['class' => $iconClass]) . ' ' . $titleContent;
            }
            $parts[] = Html::tag('div', Html::tag('h3', $titleContent, ['class' => 'card-title']), [
                'class' => 'card-header',
            ]);
        }

        $bodyContent = $this->body;
        if ($this->encodeBody && $bodyContent !== '') {
            $bodyContent = Html::encode($bodyContent);
        }
        $parts[] = Html::tag('div', $bodyContent, array_merge(['class' => 'card-body'], $this->bodyOptions));

        if ($this->footer !== null && $this->footer !== '') {
            $footerContent = $this->encodeFooter ? Html::encode($this->footer) : $this->footer;
            $parts[] = Html::tag('div', $footerContent, ['class' => 'card-footer']);
        }

        $card = Html::tag('div', implode("\n", $parts), array_merge(['class' => $cardClass], $this->options));

        if ($this->wrapperClass !== null && $this->wrapperClass !== '') {
            return Html::tag('div', $card, ['class' => self::sanitizeClass($this->wrapperClass, '')]);
        }

        return $card;
    }
}
