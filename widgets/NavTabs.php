<?php

namespace cinghie\adminlte3\widgets;

use yii\bootstrap4\Widget;
use yii\helpers\Html;

/**
 * NavTabs widget for AdminLTE 3 / Bootstrap 4.
 */
class NavTabs extends Widget
{
    public $items = [];
    public $encodeLabels = true;
    public $encodeContent = true;
    public $navOptions = ['class' => 'nav nav-tabs'];
    public $tabContentOptions = ['class' => 'tab-content'];
    public $wrapperClass;

    protected static function sanitizeClass($value, $default = '')
    {
        if ($value === null || $value === '') {
            return $default;
        }
        $sanitized = preg_replace('/[^A-Za-z0-9_\- ]/', '', (string) $value);
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
        if ($this->items === []) {
            return '';
        }

        $activeIndex = 0;
        foreach ($this->items as $i => $item) {
            if (!empty($item['active'])) {
                $activeIndex = $i;
                break;
            }
        }

        $navItems = '';
        $panes = '';
        foreach ($this->items as $i => $item) {
            $label = isset($item['label']) ? $item['label'] : 'Tab ' . ($i + 1);
            $content = isset($item['content']) ? $item['content'] : '';
            $icon = isset($item['icon']) ? $item['icon'] : null;
            $active = $i === $activeIndex;
            $fallbackId = $this->getId() . '-tab-' . $i;
            $id = !empty($item['id']) ? self::sanitizeClass($item['id'], $fallbackId) : $fallbackId;

            $linkContent = '';
            if ($icon !== null && $icon !== '') {
                $iconClass = self::sanitizeClass($icon);
                if ($iconClass !== '') {
                    $linkContent .= Html::tag('i', '', ['class' => $iconClass]) . ' ';
                }
            }
            $linkContent .= $this->encodeLabels ? Html::encode($label) : $label;

            $linkOptions = [
                'class' => 'nav-link' . ($active ? ' active' : ''),
                'data-toggle' => 'tab',
                'role' => 'tab',
                'aria-controls' => $id,
                'aria-selected' => $active ? 'true' : 'false',
            ];
            if ($active) {
                $linkOptions['aria-current'] = 'page';
            }

            $navItems .= Html::tag('li', Html::a($linkContent, '#' . $id, $linkOptions), ['class' => 'nav-item']);

            $paneContent = $this->encodeContent ? Html::encode($content) : $content;
            $panes .= Html::tag('div', $paneContent, [
                'class' => 'tab-pane fade' . ($active ? ' show active' : ''),
                'id' => $id,
                'role' => 'tabpanel',
            ]);
        }

        $out = Html::tag('ul', $navItems, $this->navOptions)
            . "\n"
            . Html::tag('div', $panes, $this->tabContentOptions);

        if ($this->wrapperClass !== null && $this->wrapperClass !== '') {
            $out = Html::tag('div', $out, ['class' => self::sanitizeClass($this->wrapperClass)]);
        }
        return $out;
    }
}
