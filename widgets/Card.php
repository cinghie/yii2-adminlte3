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

use Yii;
use yii\bootstrap4\Widget;
use yii\helpers\Html;

/**
 * AdminLTE 3 Card widget (Bootstrap 4).
 *
 * Supports both styles:
 *
 * ```php
 * echo Card::widget([
 *     'title' => 'Title',
 *     'type' => Card::TYPE_INFO,
 *     'body' => 'Content',
 * ]);
 *
 * Card::begin(['title' => 'Title', 'outline' => true, 'collapsible' => true]);
 * echo 'Content between begin/end';
 * Card::end();
 * ```
 *
 * For GridView-in-body + footer action buttons, prefer {@see Box}.
 *
 * @see https://adminlte.io/docs/3.1/components/cards.html
 */
class Card extends Widget
{
    public const TYPE_PRIMARY = 'primary';
    public const TYPE_SECONDARY = 'secondary';
    public const TYPE_SUCCESS = 'success';
    public const TYPE_INFO = 'info';
    public const TYPE_WARNING = 'warning';
    public const TYPE_DANGER = 'danger';
    public const TYPE_DARK = 'dark';
    public const TYPE_LIGHT = 'light';

    /** @deprecated Use TYPE_INFO — kept for BC with older Card API */
    public const COLOR_INFO = 'card-info';
    /** @deprecated Use TYPE_SUCCESS */
    public const COLOR_SUCCESS = 'card-success';
    /** @deprecated Use TYPE_WARNING */
    public const COLOR_WARNING = 'card-warning';
    /** @deprecated Use TYPE_DANGER */
    public const COLOR_DANGER = 'card-danger';

    /** @deprecated Use type constants — kept for BC */
    public const COLORS = [
        'info' => self::COLOR_INFO,
        'success' => self::COLOR_SUCCESS,
        'warning' => self::COLOR_WARNING,
        'danger' => self::COLOR_DANGER,
    ];

    /** @var string|null Wrapper column class (e.g. col-md-6). Null = no wrapper. */
    public $wrapperClass;

    /**
     * Card color type: primary|secondary|success|info|warning|danger|dark|light.
     * Also accepts legacy full class strings such as `card-info` or `card-outline card-info`
     * (via {@see $cardClass} or this property).
     *
     * @var string|null
     */
    public $type;

    /** @var bool Use outline style (`card-outline card-{type}`) */
    public $outline = false;

    /**
     * Legacy full card class string (e.g. `card-info`, `card-outline card-success`).
     * Prefer {@see $type} + {@see $outline}. Merged after base `card` class.
     *
     * @var string|null
     */
    public $cardClass;

    /** @var string|null Header title. Null/empty = no header unless tools are enabled. */
    public $title;

    /** @var string|null Header icon class (e.g. fas fa-chart-pie) */
    public $titleIcon;

    /** @var bool Encode title (XSS). Set false only for trusted HTML. */
    public $encodeTitle = true;

    /** @var bool Show collapse tool (`data-card-widget="collapse"`) */
    public $collapsible = false;

    /** @var bool Show remove tool (`data-card-widget="remove"`) */
    public $removable = false;

    /** @var bool Show maximize tool (`data-card-widget="maximize"`) */
    public $maximizable = false;

    /**
     * Extra header tools HTML (already trusted / encoded by caller).
     * Rendered inside `.card-tools` before collapse/remove/maximize buttons.
     *
     * @var string
     */
    public $headerTools = '';

    /** @var bool Start collapsed (adds `collapsed-card` + collapsed icon state) */
    public $collapsed = false;

    /**
     * Body content when using `Card::widget([...])`.
     * Ignored when using `Card::begin()` / `Card::end()` (captured buffer wins if non-empty).
     *
     * @var string
     */
    public $body = '';

    /** @var bool Encode body string. Set false only for trusted HTML. */
    public $encodeBody = true;

    /** @var string|null Footer content. Null/empty = no footer. */
    public $footer;

    /** @var bool Encode footer. Set false only for trusted HTML. */
    public $encodeFooter = true;

    /** @var array HTML options for the outer `.card` element */
    public $options = [];

    /** @var array HTML options for `.card-header` */
    public $headerOptions = [];

    /** @var array HTML options for `.card-body` */
    public $bodyOptions = [];

    /** @var array HTML options for `.card-footer` */
    public $footerOptions = [];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        ob_start();
        ob_implicit_flush(false);
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $captured = ob_get_clean();
        if ($captured !== false && trim((string) $captured) !== '') {
            // begin/end content is trusted view output
            $bodyHtml = (string) $captured;
        } else {
            $bodyHtml = $this->encodeBody ? Html::encode((string) $this->body) : (string) $this->body;
        }

        Html::addCssClass($this->options, $this->resolveCardCssClasses());

        $html = $this->renderHeader();
        $html .= $this->renderBody($bodyHtml);
        $html .= $this->renderFooter();

        $card = Html::tag('div', $html, $this->options);

        if ($this->wrapperClass !== null && $this->wrapperClass !== '') {
            return Html::tag('div', $card, [
                'class' => self::sanitizeClass($this->wrapperClass, ''),
            ]);
        }

        return $card;
    }

    /**
     * @return string[]
     */
    protected function resolveCardCssClasses(): array
    {
        $classes = ['card'];

        if ($this->collapsed) {
            $classes[] = 'collapsed-card';
        }

        // Legacy cardClass wins as explicit full modifier when set
        if ($this->cardClass !== null && $this->cardClass !== '') {
            foreach (preg_split('/\s+/', self::sanitizeClass($this->cardClass, ''), -1, PREG_SPLIT_NO_EMPTY) as $c) {
                $classes[] = $c;
            }

            return array_values(array_unique($classes));
        }

        $type = $this->normalizeType($this->type);
        if ($type !== null) {
            if ($this->outline) {
                $classes[] = 'card-outline';
            }
            $classes[] = 'card-' . $type;
        }

        return array_values(array_unique(array_filter($classes)));
    }

    /**
     * @param string|null $type
     * @return string|null
     */
    protected function normalizeType($type)
    {
        if ($type === null || $type === '') {
            return null;
        }

        $type = strtolower(trim((string) $type));
        // Accept legacy "card-info" / "box-info"
        if (strpos($type, 'card-') === 0) {
            $type = substr($type, 5);
        } elseif (strpos($type, 'box-') === 0) {
            $type = substr($type, 4);
        }

        $allowed = [
            self::TYPE_PRIMARY,
            self::TYPE_SECONDARY,
            self::TYPE_SUCCESS,
            self::TYPE_INFO,
            self::TYPE_WARNING,
            self::TYPE_DANGER,
            self::TYPE_DARK,
            self::TYPE_LIGHT,
        ];

        return in_array($type, $allowed, true) ? $type : null;
    }

    /**
     * @return string
     */
    protected function renderHeader()
    {
        $hasTitle = $this->title !== null && $this->title !== '';
        $hasTools = $this->collapsible || $this->removable || $this->maximizable
            || ($this->headerTools !== null && $this->headerTools !== '');

        if (!$hasTitle && !$hasTools) {
            return '';
        }

        $titleHtml = '';
        if ($hasTitle) {
            $titleContent = $this->encodeTitle ? Html::encode($this->title) : $this->title;
            if ($this->titleIcon !== null && $this->titleIcon !== '') {
                $iconClass = self::sanitizeClass($this->titleIcon, '');
                if ($iconClass !== '') {
                    $titleContent = Html::tag('i', '', ['class' => $iconClass . ' mr-1']) . ' ' . $titleContent;
                }
            }
            $titleHtml = Html::tag('h3', $titleContent, ['class' => 'card-title']);
        }

        $toolsHtml = '';
        if ($hasTools) {
            $tools = (string) $this->headerTools;
            if ($this->collapsible) {
                $icon = $this->collapsed ? 'fas fa-plus' : 'fas fa-minus';
                $tools .= Html::button(
                    Html::tag('i', '', ['class' => $icon]),
                    [
                        'type' => 'button',
                        'class' => 'btn btn-tool',
                        'data-card-widget' => 'collapse',
                        'title' => Yii::t('traits', 'Collapse'),
                        'aria-label' => Yii::t('traits', 'Collapse'),
                    ]
                );
            }
            if ($this->maximizable) {
                $tools .= Html::button(
                    Html::tag('i', '', ['class' => 'fas fa-expand']),
                    [
                        'type' => 'button',
                        'class' => 'btn btn-tool',
                        'data-card-widget' => 'maximize',
                        'title' => Yii::t('traits', 'Maximize'),
                        'aria-label' => Yii::t('traits', 'Maximize'),
                    ]
                );
            }
            if ($this->removable) {
                $tools .= Html::button(
                    Html::tag('i', '', ['class' => 'fas fa-times']),
                    [
                        'type' => 'button',
                        'class' => 'btn btn-tool',
                        'data-card-widget' => 'remove',
                        'title' => Yii::t('traits', 'Remove'),
                        'aria-label' => Yii::t('traits', 'Remove'),
                    ]
                );
            }
            $toolsHtml = Html::tag('div', $tools, ['class' => 'card-tools']);
        }

        $headerOptions = $this->headerOptions;
        Html::addCssClass($headerOptions, 'card-header');

        return Html::tag('div', $titleHtml . $toolsHtml, $headerOptions);
    }

    /**
     * @param string $bodyHtml already encoded or trusted
     * @return string
     */
    protected function renderBody(string $bodyHtml)
    {
        $bodyOptions = $this->bodyOptions;
        Html::addCssClass($bodyOptions, 'card-body');

        return Html::tag('div', $bodyHtml, $bodyOptions);
    }

    /**
     * @return string
     */
    protected function renderFooter()
    {
        if ($this->footer === null || $this->footer === '') {
            return '';
        }

        $footerContent = $this->encodeFooter ? Html::encode($this->footer) : $this->footer;
        $footerOptions = $this->footerOptions;
        Html::addCssClass($footerOptions, 'card-footer');

        return Html::tag('div', $footerContent, $footerOptions);
    }

    /**
     * Sanitize string for use in class attribute (alphanumeric, space, hyphen only).
     *
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
}
