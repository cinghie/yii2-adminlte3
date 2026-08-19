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

use cinghie\adminlte3\widgets\support\SafeHtml;
use cinghie\adminlte3\widgets\support\Translation;
use yii\bootstrap4\Widget;
use yii\helpers\Html;

/**
 * SidebarSearch widget for AdminLTE 3 with Bootstrap 4.
 *
 * Renders the sidebar search form. When the sidebar is collapsed (sidebar-mini),
 * AdminLTE 3 hides the input and shows it on hover/focus via its CSS.
 * Requires AdminLTE's sidebar-search plugin (data-widget="sidebar-search").
 *
 * @see https://adminlte.io/docs/3.1/components/main-sidebar.html
 */
class SidebarSearch extends Widget
{
    /** @var string Placeholder for the search input. */
    public $placeholder;

    /** @var string|null Search form action URL (reserved for compatibility). */
    public $action;

    /** @var string Name of the search input. */
    public $name = 'q';

    /** @var array HTML attributes for the wrapper div. */
    public $wrapperOptions = [];

    /** @var array HTML attributes for the input. */
    public $inputOptions = [];

    /** @var string|null Canonical Font Awesome icon class list. */
    public $icon;

    /**
     * @var string|null Legacy search icon class list.
     * @deprecated Use {@see $icon}.
     */
    public $searchIconClass;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->placeholder === null) {
            $this->placeholder = Translation::t('Search');
        }
        parent::init();
    }

    /**
     * @return string
     */
    public function run()
    {
        $placeholder = Html::encode($this->placeholder);
        $inputOptions = array_merge([
            'class' => 'form-control form-control-sidebar',
            'type' => 'search',
            'placeholder' => $placeholder,
            'aria-label' => $placeholder,
        ], $this->inputOptions);

        $input = Html::input('search', $this->name, null, $inputOptions);
        $icon = $this->icon !== null && $this->icon !== ''
            ? $this->icon
            : $this->searchIconClass;
        $icon = SafeHtml::iconClass($icon, 'fas fa-search fa-fw');

        $button = Html::button(
            Html::tag('i', '', ['class' => $icon]),
            ['class' => 'btn btn-sidebar', 'type' => 'button', 'aria-label' => $placeholder]
        );

        $append = Html::tag('div', $button, ['class' => 'input-group-append']);
        $inputGroup = Html::tag('div', $input . $append, [
            'class' => 'input-group',
            'data-widget' => 'sidebar-search',
        ]);

        $wrapperOptions = array_merge(['class' => 'form-inline sidebar-search-wrapper'], $this->wrapperOptions);

        return Html::tag('div', $inputGroup, $wrapperOptions);
    }
}
