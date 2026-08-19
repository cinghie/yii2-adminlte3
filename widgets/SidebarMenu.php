<?php

namespace cinghie\adminlte3\widgets;

use cinghie\adminlte3\widgets\support\SafeHtml;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Menu;

/**
 * Renders an AdminLTE 3 sidebar navigation menu.
 *
 * Labels follow Yii Menu encoding rules, while icon and badge classes are
 * normalized before they are used in HTML attributes. Active-route matching
 * handles controller default actions and module default routes by path segment
 * rather than substring matching.
 */
class SidebarMenu extends Menu
{
    /** @var string Template used for leaf links. */
    public $linkTemplate = '<a class="nav-link{activeClass}" href="{url}">{icon}<p>{label}{caret}{badge}</p></a>';

    /** @var string Template used for encoded/normalized labels. */
    public $labelTemplate = '{label}';

    /** @var string Template used for links that own a submenu. */
    public $parentLinkTemplate = '<a class="nav-link{activeClass}" href="{url}">{icon}<p>{label}<i class="right fas fa-angle-left"></i>{badge}</p></a>';

    /**
     * @var string Template used for nested menu lists.
     *
     * Visibility is controlled by AdminLTE's `menu-open` class on the parent
     * item rather than an inline `style` attribute, which is friendlier to CSP.
     */
    public $submenuTemplate = "\n<ul class=\"nav nav-treeview\">\n{items}\n</ul>\n";

    /** @var bool Whether an active child should activate its parent item. */
    public $activateParents = true;

    /** @var string Trusted fallback icon HTML. */
    public $defaultIconHtml = '';

    /** @var bool Whether badge text should be HTML-encoded. */
    public $encodeBadges = true;

    /** @var array HTML options for the root menu element. */
    public $options = [
        'class' => 'nav nav-pills nav-sidebar flex-column',
        'data-widget' => 'treeview',
        'role' => 'menu',
        'data-accordion' => 'false',
    ];

    /** @var string Prefix prepended to item icon classes. */
    public static $iconClassPrefix = 'nav-icon ';

    /** @var string Default icon class for submenu entries. */
    public $submenuIconClass = 'far fa-circle nav-icon';

    /** @var string|false Current route without its controller default action. */
    private $noDefaultAction;

    /** @var string|false Current route without its module default route. */
    private $noDefaultRoute;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->resolveRequestContext();
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $this->resolveRequestContext();
        $this->resolveDefaultRoutes();

        $hasActiveChild = false;
        $items = $this->normalizeItems($this->items, $hasActiveChild);

        if (!empty($items)) {
            $options = $this->options;
            $tag = ArrayHelper::remove($options, 'tag', 'ul');
            echo Html::tag($tag, $this->renderItems($items), $options);
        }
    }

    /**
     * Fills route and query parameters from the active request when omitted.
     *
     * @return void
     */
    protected function resolveRequestContext(): void
    {
        if ($this->route === null && Yii::$app->controller !== null) {
            $this->route = Yii::$app->controller->getRoute();
        }
        if ($this->params === null && Yii::$app->request !== null) {
            $this->params = Yii::$app->request->getQueryParams();
        }
    }

    /**
     * Computes shorthand routes that omit default actions/module routes.
     *
     * @return void
     */
    protected function resolveDefaultRoutes(): void
    {
        $this->noDefaultAction = false;
        $this->noDefaultRoute = false;

        if (Yii::$app->controller === null || $this->route === null) {
            return;
        }

        $segments = array_values(array_filter(
            explode('/', trim($this->route, '/')),
            static fn (string $segment): bool => $segment !== ''
        ));
        if ($segments === []) {
            return;
        }

        $defaultAction = (string) Yii::$app->controller->defaultAction;
        if (end($segments) === $defaultAction) {
            array_pop($segments);
            $this->noDefaultAction = implode('/', $segments);
        }

        $module = Yii::$app->controller->module;
        if ($module !== null) {
            $defaultRoute = trim((string) $module->defaultRoute, '/');
            if ($defaultRoute !== '') {
                $routeSegments = array_values(array_filter(
                    explode('/', $defaultRoute),
                    static fn (string $segment): bool => $segment !== ''
                ));
                $candidate = $segments;
                $count = count($routeSegments);
                if ($count > 0 && array_slice($candidate, -$count) === $routeSegments) {
                    $this->noDefaultRoute = implode('/', array_slice($candidate, 0, -$count));
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function renderItem($item)
    {
        $optionsClass = (string) ArrayHelper::getValue($item, 'options.class', '');
        $isHeader = strpos($optionsClass, 'nav-header') !== false
            || preg_match('/(^|\s)header(\s|$)/', $optionsClass);
        if ($isHeader) {
            return strtr($this->labelTemplate, ['{label}' => $item['label']]);
        }

        $hasItems = !empty($item['items']);
        $badge = ArrayHelper::getValue($item, 'badge', '');
        if ($badge !== '') {
            $badgeOptions = ArrayHelper::getValue($item, 'badgeOptions', ['class' => 'right badge badge-danger']);
            $badgeOptions['class'] = SafeHtml::cssClass(
                ArrayHelper::getValue($badgeOptions, 'class', ''),
                'right badge badge-danger'
            );
            $badgeContent = $this->encodeBadges ? Html::encode((string) $badge) : (string) $badge;
            $badge = ' ' . Html::tag('span', $badgeContent, $badgeOptions);
        }

        $template = ArrayHelper::getValue(
            $item,
            'template',
            $hasItems ? $this->parentLinkTemplate : $this->linkTemplate
        );

        $icon = $this->defaultIconHtml;
        if (!empty($item['icon'])) {
            $iconClass = SafeHtml::iconClass(static::$iconClassPrefix . $item['icon']);
            $icon = $iconClass === '' ? '' : Html::tag('i', '', ['class' => $iconClass]) . ' ';
        }

        $url = isset($item['url']) ? Url::to($item['url']) : '#';
        $label = strtr($this->labelTemplate, ['{label}' => $item['label']]);
        $activeClass = !empty($item['active']) ? ' active' : '';

        return strtr($template, [
            '{url}' => Html::encode($url),
            '{label}' => $label,
            '{icon}' => $icon,
            '{caret}' => '',
            '{badge}' => $badge,
            '{activeClass}' => $activeClass,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function renderItems($items)
    {
        $n = count($items);
        $lines = [];

        foreach ($items as $i => $item) {
            $options = array_merge($this->itemOptions, ArrayHelper::getValue($item, 'options', []));
            $tag = ArrayHelper::remove($options, 'tag', 'li');
            $optionsClass = (string) ArrayHelper::getValue($options, 'class', '');
            $isHeader = strpos($optionsClass, 'nav-header') !== false
                || preg_match('/(^|\s)header(\s|$)/', $optionsClass);

            if ($isHeader) {
                if (strpos($optionsClass, 'nav-header') === false) {
                    $options['class'] = trim(
                        (string) preg_replace('/(^|\s)header(\s|$)/', ' nav-header ', $optionsClass)
                    );
                }
                $lines[] = Html::tag($tag, $this->renderItem($item), $options);
                continue;
            }

            $classes = ['nav-item'];
            if ($item['active']) {
                $classes[] = $this->activeCssClass;
            }
            if (!empty($item['items']) && $item['active']) {
                $classes[] = 'menu-open';
            }
            if ($i === 0 && $this->firstItemCssClass !== null) {
                $classes[] = $this->firstItemCssClass;
            }
            if ($i === $n - 1 && $this->lastItemCssClass !== null) {
                $classes[] = $this->lastItemCssClass;
            }
            Html::addCssClass($options, $classes);

            $menu = $this->renderItem($item);
            if (!empty($item['items'])) {
                $menu .= strtr($this->submenuTemplate, [
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

            $item['label'] = $item['label'] ?? '';
            $encodeLabel = $item['encode'] ?? $this->encodeLabels;
            $items[$i]['label'] = $encodeLabel ? Html::encode($item['label']) : $item['label'];
            $items[$i]['icon'] = isset($item['icon']) ? SafeHtml::iconClass($item['icon']) : '';
            $items[$i]['options'] = ArrayHelper::getValue($item, 'options', []);
            $items[$i]['badge'] = ArrayHelper::getValue($item, 'badge', '');
            $items[$i]['badgeOptions'] = ArrayHelper::getValue(
                $item,
                'badgeOptions',
                ['class' => 'right badge badge-danger']
            );
            $hasActiveChild = false;

            if (isset($item['items'])) {
                $items[$i]['items'] = $this->normalizeItems($item['items'], $hasActiveChild);
                $class = (string) ArrayHelper::getValue($items[$i], 'options.class', '');
                $isHeader = strpos($class, 'nav-header') !== false
                    || preg_match('/(^|\s)header(\s|$)/', $class);
                if (empty($items[$i]['items']) && $this->hideEmptyItems && !$isHeader) {
                    unset($items[$i]['items']);
                    if (!isset($item['url'])) {
                        unset($items[$i]);
                        continue;
                    }
                }
            }

            if (!isset($item['active'])) {
                if (($this->activateParents && $hasActiveChild)
                    || ($this->activateItems && $this->isItemActive($item))) {
                    $active = $items[$i]['active'] = true;
                } else {
                    $items[$i]['active'] = false;
                }
            } elseif ($item['active']) {
                $active = true;
                $items[$i]['active'] = true;
            }
        }

        return array_values($items);
    }

    /**
     * {@inheritdoc}
     */
    protected function isItemActive($item)
    {
        if (!isset($item['url']) || !is_array($item['url']) || !isset($item['url'][0])) {
            return false;
        }

        $route = (string) $item['url'][0];
        if ($route === '') {
            return false;
        }
        if ($route[0] !== '/' && Yii::$app->controller !== null) {
            $route = ltrim(
                Yii::$app->controller->module !== null
                    ? Yii::$app->controller->module->getUniqueId() . '/' . $route
                    : $route,
                '/'
            );
        }
        $route = ltrim($route, '/');

        $routeMatches = $route === $this->route
            || ($this->noDefaultRoute !== false && $route === $this->noDefaultRoute)
            || ($this->noDefaultAction !== false && $route === $this->noDefaultAction);

        if ($this->noDefaultAction !== false && !$routeMatches) {
            $routeMatches = $route === $this->noDefaultAction . '/' . Yii::$app->controller->defaultAction;
        }
        if (!$routeMatches) {
            return false;
        }

        $url = $item['url'];
        unset($url['#']);
        foreach (array_slice($url, 1, null, true) as $name => $value) {
            if ($value !== null && (!isset($this->params[$name]) || $this->params[$name] !== $value)) {
                return false;
            }
        }

        return true;
    }
}
