<?php

namespace cinghie\adminlte3\widgets;

use cinghie\adminlte3\widgets\support\SafeHtml;
use yii\bootstrap4\Widget;
use yii\helpers\Html;

/**
 * Renders a single AdminLTE 3 navbar link.
 *
 * The widget keeps HTML content in {@see $title} for backward compatibility,
 * while the link target is normalized through the package's internal URL
 * policy. Applications should pass trusted markup only in {@see $title}.
 */
class NavbarButton extends Widget
{
    /** @var string Trusted link content. */
    public $title;

    /** @var string|array Link URL or Yii route array. */
    public $url = '#';

    /** @var string|null Optional anchor target, for example `_blank`. */
    public $target;

    /** @var array HTML options merged into the anchor element. */
    public $options = [];

    /**
     * @var array|null Legacy anchor options.
     * @deprecated Use {@see $options}.
     */
    public $option;

    /** @var bool Whether to wrap the anchor in `<li class="nav-item">`. */
    public $renderAsLi = true;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if ($this->title === null) {
            $this->title = '<i class="fas fa-external-link-alt"></i>';
        }
        if ($this->options === null) {
            $this->options = [];
        }
        if (is_array($this->option) && $this->option !== []) {
            // Canonical options win when both names configure the same key.
            $this->options = array_merge($this->option, $this->options);
        }

        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $options = array_merge(['class' => 'nav-link'], $this->options);
        $options = array_merge($options, SafeHtml::externalLinkOptions($this->target));

        $link = Html::a($this->title, SafeHtml::linkUrl($this->url, '#'), $options);

        return $this->renderAsLi
            ? Html::tag('li', $link, ['class' => 'nav-item'])
            : $link;
    }
}
