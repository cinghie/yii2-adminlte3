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
 * NavTabs widget for AdminLTE 3 / Bootstrap 4.
 *
 * Renders nav tabs with tab panes: ul.nav.nav-tabs and div.tab-content > div.tab-pane.
 * Each item: label, content, optional icon, optional active flag.
 * All labels and content are HTML-encoded by default.
 *
 * @see https://getbootstrap.com/docs/4.6/components/navs/#tabs
 * @see https://adminlte.io/docs/3.0/
 */
class NavTabs extends Widget
{
    /**
     * @var array Tab items. Each element: [
     *   'label' => string (required),
     *   'content' => string (required),
     *   'icon' => string|null (optional, e.g. 'fas fa-home'),
     *   'active' => bool (optional, default false; first item if none set),
     *   'id' => string|null (optional, tab pane id; auto-generated if not set),
     * ]
     */
    public $items = [];

    /** @var bool Whether to HTML-encode tab labels. Default true. */
    public $encodeLabels = true;

    /** @var bool Whether to HTML-encode tab content. Default true. */
    public $encodeContent = true;

    /** @var array HTML options for the nav container (ul.nav.nav-tabs) */
    public $navOptions = ['class' => 'nav nav-tabs'];

    /** @var array HTML options for the tab content container (div.tab-content) */
    public $tabContentOptions = ['class' => 'tab-content'];

    /** @var string|null Wrapper class (e.g. card card-tabs). Null = no wrapper. */
    public $wrapperClass;

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
        if (!is_array($this->items)) {
            $this->items = [];
        }
    }

    public function run()
    {
        if (empty($this->items)) {
            return '';
        }

        $activeIndex = null;
        foreach ($this->items as $i => $item) {
            if (isset($item['active']) && $item['active']) {
                $activeIndex = $i;
                break;
            }
        }
        if ($activeIndex === null) {
            $activeIndex = 0;
        }

        $navItems = '';
        $panes = '';
        foreach ($this->items as $i => $item) {
            $label = isset($item['label']) ? $item['label'] : 'Tab ' . ($i + 1);
            $content = isset($item['content']) ? $item['content'] : '';
            $icon = isset($item['icon']) ? $item['icon'] : null;
            $active = ($i === $activeIndex);
            $id = isset($item['id']) && $item['id'] !== '' ? $item['id'] : $this->getId() . '-tab-' . $i;
            $id = self::sanitizeClass($id, $this->getId() . '-tab-' . $i);

            $linkContent = '';
            if ($icon !== null && $icon !== '') {
                $iconClass = self::sanitizeClass($icon, '');
                $linkContent .= Html::tag('i', '', ['class' => $iconClass]) . ' ';
            }
            $linkContent .= $this->encodeLabels ? Html::encode($label) : $label;

            $linkOptions = [
                'class' => 'nav-link' . ($active ? ' active' : ''),
                'data-toggle' => 'tab',
                'href' => '#' . $id,
                'role' => 'tab',
                'aria-selected' => $active ? 'true' : 'false',
            ];
            if ($active) {
                $linkOptions['aria-current'] = 'page';
            }

            $navItems .= Html::tag('li', Html::a($linkContent, '#' . $id, array_merge($linkOptions, ['encode' => false])), [
                'class' => 'nav-item',
            ]);

            $paneContent = $this->encodeContent ? Html::encode($content) : $content;
            $paneOptions = [
                'class' => 'tab-pane fade' . ($active ? ' show active' : ''),
                'id' => $id,
                'role' => 'tabpanel',
            ];
            $panes .= Html::tag('div', $paneContent, $paneOptions);
        }

        $nav = Html::tag('ul', $navItems, $this->navOptions);
        $tabContent = Html::tag('div', $panes, $this->tabContentOptions);
        $out = $nav . "\n" . $tabContent;

        if ($this->wrapperClass !== null && $this->wrapperClass !== '') {
            $out = Html::tag('div', $out, ['class' => self::sanitizeClass($this->wrapperClass, '')]);
        }

        return $out;
    }
}
