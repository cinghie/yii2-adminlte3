<?php

namespace cinghie\adminlte3\widgets;

use cinghie\adminlte3\widgets\support\SafeHtml;
use cinghie\adminlte3\widgets\support\Translation;
use yii\bootstrap4\Widget;
use yii\helpers\Html;

/**
 * Renders an AdminLTE 3 / Bootstrap 4 card.
 *
 * Supports both direct widget rendering and Yii's begin()/end() pattern:
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
 * For GridView-in-body and legacy footer action buttons, {@see Box} remains a
 * backward-compatible facade over this widget.
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

    /** @deprecated Use {@see TYPE_INFO}. */
    public const COLOR_INFO = 'card-info';

    /** @deprecated Use {@see TYPE_SUCCESS}. */
    public const COLOR_SUCCESS = 'card-success';

    /** @deprecated Use {@see TYPE_WARNING}. */
    public const COLOR_WARNING = 'card-warning';

    /** @deprecated Use {@see TYPE_DANGER}. */
    public const COLOR_DANGER = 'card-danger';

    /** @deprecated Use the `TYPE_*` constants. */
    public const COLORS = [
        'info' => self::COLOR_INFO,
        'success' => self::COLOR_SUCCESS,
        'warning' => self::COLOR_WARNING,
        'danger' => self::COLOR_DANGER,
    ];

    /** @var string|null Wrapper grid classes; null renders the card without a wrapper. */
    public $wrapperClass;

    /** @var string|null Contextual card type. */
    public $type;

    /** @var bool Whether to render the contextual card as an outline. */
    public $outline = false;

    /**
     * @var string|null Legacy full card modifier class string.
     * @deprecated Prefer {@see $type} and {@see $outline}.
     */
    public $cardClass;

    /** @var string|null Header title. */
    public $title;

    /** @var string|null Header icon class list. */
    public $icon;

    /**
     * @var string|null Legacy header icon class list.
     * @deprecated Use {@see $icon}. The canonical `icon` name is shared by widgets.
     */
    public $titleIcon;

    /** @var bool Whether to HTML-encode the title. */
    public $encodeTitle = true;

    /** @var bool Whether to show the AdminLTE collapse tool. */
    public $collapsible = false;

    /** @var bool Whether to show the AdminLTE remove tool. */
    public $removable = false;

    /** @var bool Whether to show the AdminLTE maximize tool. */
    public $maximizable = false;

    /** @var string Trusted additional HTML rendered before standard card tools. */
    public $headerTools = '';

    /** @var bool Whether the card starts in the collapsed state. */
    public $collapsed = false;

    /** @var string Body content used by direct widget rendering. */
    public $body = '';

    /** @var bool Whether to HTML-encode direct body content. */
    public $encodeBody = true;

    /** @var string|null Footer content. */
    public $footer;

    /** @var bool Whether to HTML-encode footer content. */
    public $encodeFooter = true;

    /** @var array HTML options for the outer `.card` element. */
    public $options = [];

    /** @var array HTML options for `.card-header`. */
    public $headerOptions = [];

    /** @var array HTML options for `.card-body`. */
    public $bodyOptions = [];

    /** @var array HTML options for `.card-footer`. */
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
            // Output captured from the caller's view is intentionally trusted.
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
                'class' => self::sanitizeClass($this->wrapperClass),
            ]);
        }

        return $card;
    }

    /**
     * Resolves the final card class list, including legacy modifiers.
     *
     * @return string[]
     */
    protected function resolveCardCssClasses(): array
    {
        $classes = ['card'];
        if ($this->collapsed) {
            $classes[] = 'collapsed-card';
        }

        if ($this->cardClass !== null && $this->cardClass !== '') {
            $legacyClasses = preg_split(
                '/\s+/',
                self::sanitizeClass($this->cardClass),
                -1,
                PREG_SPLIT_NO_EMPTY
            );
            foreach ($legacyClasses ?: [] as $class) {
                $classes[] = $class;
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

        return array_values(array_unique($classes));
    }

    /**
     * Normalizes a contextual card type.
     *
     * @param string|null $type Candidate type or legacy `card-*`/`box-*` value.
     * @return string|null
     */
    protected function normalizeType($type)
    {
        if ($type === null || $type === '') {
            return null;
        }

        $type = strtolower(trim((string) $type));
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
     * Renders the optional header and AdminLTE card tools.
     *
     * @return string
     */
    protected function renderHeader()
    {
        $title = $this->renderTitle();
        $tools = $this->renderTools();
        if ($title === '' && $tools === '') {
            return '';
        }

        $headerOptions = $this->headerOptions;
        Html::addCssClass($headerOptions, 'card-header d-flex align-items-center');

        $content = $title;
        if ($tools !== '') {
            $content .= Html::tag('div', $tools, ['class' => 'card-tools ml-auto']);
        }

        return Html::tag('div', $content, $headerOptions);
    }

    /**
     * Renders the card title and optional icon.
     *
     * @return string
     */
    protected function renderTitle()
    {
        if ($this->title === null || $this->title === '') {
            return '';
        }

        $title = $this->encodeTitle ? Html::encode((string) $this->title) : (string) $this->title;
        $icon = $this->icon !== null && $this->icon !== '' ? $this->icon : $this->titleIcon;
        if ($icon !== null && $icon !== '') {
            $title = Html::tag('i', '', ['class' => self::sanitizeClass($icon)]) . ' ' . $title;
        }

        return Html::tag('h3', $title, ['class' => 'card-title']);
    }

    /**
     * Renders standard AdminLTE card tools and trusted custom tools.
     *
     * @return string
     */
    protected function renderTools()
    {
        $tools = $this->headerTools;
        if ($this->collapsible) {
            $tools .= $this->renderToolButton('collapse', 'fas fa-minus', Translation::t('Collapse'));
        }
        if ($this->maximizable) {
            $tools .= $this->renderToolButton('maximize', 'fas fa-expand', Translation::t('Maximize'));
        }
        if ($this->removable) {
            $tools .= $this->renderToolButton('remove', 'fas fa-times', Translation::t('Remove'));
        }

        return $tools;
    }

    /**
     * Renders one AdminLTE card tool button.
     *
     * @param string $action AdminLTE card action.
     * @param string $icon Icon class list.
     * @param string $label Accessible label.
     * @return string
     */
    protected function renderToolButton($action, $icon, $label)
    {
        return Html::button(
            Html::tag('i', '', ['class' => self::sanitizeClass($icon)]),
            [
                'type' => 'button',
                'class' => 'btn btn-tool',
                'data-card-widget' => $action,
                'title' => $label,
                'aria-label' => $label,
            ]
        );
    }

    /**
     * Renders the card body wrapper around trusted prepared HTML.
     *
     * @param string $bodyHtml Prepared body HTML.
     * @return string
     */
    protected function renderBody($bodyHtml)
    {
        $bodyOptions = $this->bodyOptions;
        Html::addCssClass($bodyOptions, 'card-body');

        return Html::tag('div', $bodyHtml, $bodyOptions);
    }

    /**
     * Renders the optional footer.
     *
     * @return string
     */
    protected function renderFooter()
    {
        if ($this->footer === null) {
            return '';
        }

        $footerOptions = $this->footerOptions;
        Html::addCssClass($footerOptions, 'card-footer');
        $footer = $this->encodeFooter ? Html::encode((string) $this->footer) : (string) $this->footer;

        return Html::tag('div', $footer, $footerOptions);
    }

    /**
     * Sanitizes a CSS class list using the shared package policy.
     *
     * @param string|null $value Class list.
     * @return string
     */
    protected static function sanitizeClass($value): string
    {
        return SafeHtml::cssClass($value);
    }
}
