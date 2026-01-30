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
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Menu;

/**
 * SidebarMenu widget for AdminLTE 3 with Bootstrap 4.
 *
 * Renders a sidebar navigation menu compatible with AdminLTE 3 treeview plugin.
 * Uses Font Awesome 5 icon classes (fas, far, fab).
 *
 * @see https://adminlte.io/docs/3.1/components/main-sidebar.html
 */
class SidebarMenu extends Menu
{
    /**
     * @var string Template for menu links (AdminLTE 3: icon + label in <p>)
     */
    public $linkTemplate = '<a class="nav-link" href="{url}">{icon}<p>{label}{caret}{badge}</p></a>';

    /**
     * @var string Template for label-only items (e.g. headers). Use {label} for AdminLTE 3.
     */
    public $labelTemplate = '{label}';

    /**
     * @var string Template for parent items with submenu (icon + label + angle icon)
     */
    public $parentLinkTemplate = '<a class="nav-link" href="{url}">{icon}<p>{label}<i class="right fas fa-angle-left"></i>{badge}</p></a>';

    /**
     * @var string Template for submenu container (AdminLTE 3 nav-treeview)
     */
    public $submenuTemplate = "\n<ul class=\"nav nav-treeview\" {show}>\n{items}\n</ul>\n";

    /**
     * @var bool Whether to activate parent menu items when a child is active
     */
    public $activateParents = true;

    /**
     * @var string Default HTML for icon when item has no icon
     */
    public $defaultIconHtml = '';

    /**
     * @var array Options for the root ul (AdminLTE 3 treeview)
     */
    public $options = [
        'class' => 'nav nav-pills nav-sidebar flex-column',
        'data-widget' => 'treeview',
        'role' => 'menu',
        'data-accordion' => 'false',
    ];

    /**
     * @var string Prefix for icon classes (e.g. 'nav-icon ' for AdminLTE 3)
     */
    public static $iconClassPrefix = 'nav-icon ';

    /**
     * @var string Default icon class for submenu items (AdminLTE 3 uses far fa-circle)
     */
    public $submenuIconClass = 'far fa-circle nav-icon';

    /**
     * @var bool
     */
    private $noDefaultAction;

    /**
     * @var bool
     */
    private $noDefaultRoute;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if ($this->route === null && Yii::$app->controller !== null) {
            $this->route = Yii::$app->controller->getRoute();
        }

        if ($this->params === null) {
            $this->params = Yii::$app->request->getQueryParams();
        }

        $posDefaultAction = strpos($this->route, Yii::$app->controller->defaultAction);
        if ($posDefaultAction) {
            $this->noDefaultAction = rtrim(substr($this->route, 0, $posDefaultAction), '/');
        } else {
            $this->noDefaultAction = false;
        }

        if (Yii::$app->controller->module !== null) {
            $posDefaultRoute = strpos($this->route, Yii::$app->controller->module->defaultRoute);
            if ($posDefaultRoute) {
                $this->noDefaultRoute = rtrim(substr($this->route, 0, $posDefaultRoute), '/');
            } else {
                $this->noDefaultRoute = false;
            }
        } else {
            $this->noDefaultRoute = false;
        }

        $items = $this->normalizeItems($this->items, $hasActiveChild);

        if (!empty($items)) {
            $options = $this->options;
            $tag = ArrayHelper::remove($options, 'tag', 'ul');
            echo Html::tag($tag, $this->renderItems($items), $options);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function renderItem($item)
    {
        $isHeader = isset($item['options']['class']) && strpos($item['options']['class'], 'nav-header') !== false;
        if ($isHeader) {
            return strtr($this->labelTemplate, ['{label}' => $item['label']]);
        }

        $hasItems = !empty($item['items']);
        $caret = $hasItems ? '' : '';
        $badge = ArrayHelper::getValue($item, 'badge', '');
        if ($badge !== '') {
            $badgeOptions = ArrayHelper::getValue($item, 'badgeOptions', ['class' => 'right badge badge-danger']);
            $badge = ' ' . Html::tag('span', $badge, $badgeOptions);
        }

        if ($hasItems) {
            $template = ArrayHelper::getValue($item, 'template', $this->parentLinkTemplate);
        } else {
            $template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);
        }

        $icon = empty($item['icon'])
            ? $this->defaultIconHtml
            : '<i class="' . static::$iconClassPrefix . $item['icon'] . '"></i> ';
        $url = isset($item['url']) ? Url::to($item['url']) : '#';
        $label = strtr($this->labelTemplate, ['{label}' => $item['label']]);

        return strtr($template, [
            '{url}' => $url,
            '{label}' => $label,
            '{icon}' => $icon,
            '{caret}' => $caret,
            '{badge}' => $badge,
        ]);
    }

    /**
     * Recursively renders the menu items.
     *
     * @param array $items the menu items to be rendered recursively
     * @return string the rendering result
     */
    protected function renderItems($items)
    {
        $n = count($items);
        $lines = [];

        foreach ($items as $i => $item) {
            $options = array_merge($this->itemOptions, ArrayHelper::getValue($item, 'options', []));
            $tag = ArrayHelper::remove($options, 'tag', 'li');

            $isHeader = isset($options['class']) && strpos($options['class'], 'nav-header') !== false;
            if ($isHeader) {
                $lines[] = Html::tag($tag, $this->renderItem($item), $options);
                continue;
            }

            $class = [];
            if ($item['active']) {
                $class[] = $this->activeCssClass;
            }
            if (!empty($item['items']) && $item['active']) {
                $class[] = 'menu-open';
            }
            if ($i === 0 && $this->firstItemCssClass !== null) {
                $class[] = $this->firstItemCssClass;
            }
            if ($i === $n - 1 && $this->lastItemCssClass !== null) {
                $class[] = $this->lastItemCssClass;
            }
            $class[] = 'nav-item';

            if (!empty($class)) {
                if (empty($options['class'])) {
                    $options['class'] = implode(' ', $class);
                } else {
                    $options['class'] .= ' ' . implode(' ', $class);
                }
            }

            $menu = $this->renderItem($item);

            if (!empty($item['items'])) {
                $menu .= strtr($this->submenuTemplate, [
                    '{show}' => $item['active'] ? 'style="display: block;"' : '',
                    '{items}' => $this->renderItems($item['items']),
                ]);
            }

            $lines[] = Html::tag($tag, $menu, $options);
        }

        return implode("\n", $lines);
    }

    /**
     * {@inheritdoc}
     */
    protected function normalizeItems($items, &$active)
    {
        foreach ($items as $i => $item) {
            if (isset($item['visible']) && !$item['visible']) {
                unset($items[$i]);
                continue;
            }

            if (!isset($item['label'])) {
                $item['label'] = '';
            }

            $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
            $items[$i]['label'] = $encodeLabel ? Html::encode($item['label']) : $item['label'];
            $items[$i]['icon'] = isset($item['icon']) ? $item['icon'] : '';
            $items[$i]['options'] = ArrayHelper::getValue($item, 'options', []);
            $items[$i]['badge'] = ArrayHelper::getValue($item, 'badge', '');
            $items[$i]['badgeOptions'] = ArrayHelper::getValue($item, 'badgeOptions', ['class' => 'right badge badge-danger']);
            $hasActiveChild = false;

            if (isset($item['items'])) {
                $items[$i]['items'] = $this->normalizeItems($item['items'], $hasActiveChild);
                $isHeader = isset($items[$i]['options']['class']) && strpos($items[$i]['options']['class'], 'nav-header') !== false;
                if (empty($items[$i]['items']) && $this->hideEmptyItems && !$isHeader) {
                    unset($items[$i]['items']);
                    if (!isset($item['url'])) {
                        unset($items[$i]);
                        continue;
                    }
                }
            }

            if (!isset($item['active'])) {
                if (($this->activateParents && $hasActiveChild) || ($this->activateItems && $this->isItemActive($item))) {
                    $active = $items[$i]['active'] = true;
                } else {
                    $items[$i]['active'] = false;
                }
            } elseif ($item['active']) {
                $active = true;
            }
        }

        return array_values($items);
    }

    /**
     * Checks whether a menu item is active.
     *
     * @param array $item the menu item to be checked
     * @return bool whether the menu item is active
     */
    protected function isItemActive($item)
    {
        if (isset($item['url']) && is_array($item['url']) && isset($item['url'][0])) {
            $route = $item['url'][0];

            if ($route[0] !== '/' && Yii::$app->controller !== null) {
                $route = ltrim(
                    Yii::$app->controller->module !== null
                        ? Yii::$app->controller->module->getUniqueId() . '/' . $route
                        : $route,
                    '/'
                );
            }

            $route = ltrim($route, '/');

            if ($route !== $this->route && $route !== $this->noDefaultRoute && $route !== $this->noDefaultAction) {
                return false;
            }

            unset($item['url']['#']);

            if (count($item['url']) > 1) {
                foreach (array_splice($item['url'], 1) as $name => $value) {
                    if ($value !== null && (!isset($this->params[$name]) || $this->params[$name] !== $value)) {
                        return false;
                    }
                }
            }

            return true;
        }

        return false;
    }
}
