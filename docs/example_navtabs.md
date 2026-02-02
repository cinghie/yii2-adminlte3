# NavTabs Widget

The **NavTabs** widget renders AdminLTE 3 / Bootstrap 4 nav tabs with tab panes: `ul.nav.nav-tabs` and `div.tab-content` with `div.tab-pane` for each tab. Each item has a label, content, optional icon, and optional active flag. All labels and content are HTML-encoded by default.

**Reference:** [Bootstrap 4 – Navs and tabs](https://getbootstrap.com/docs/4.6/components/navs/#tabs)

---

## Properties

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `items` | array | [] | Tab items. Each element: `label` (required), `content` (required), `icon` (optional), `active` (optional bool), `id` (optional, tab pane id). |
| `encodeLabels` | bool | true | Whether to HTML-encode tab labels (XSS prevention). |
| `encodeContent` | bool | true | Whether to HTML-encode tab content. |
| `navOptions` | array | `['class' => 'nav nav-tabs']` | HTML options for the `ul.nav.nav-tabs` container. |
| `tabContentOptions` | array | `['class' => 'tab-content']` | HTML options for the `div.tab-content` container. |
| `wrapperClass` | string\|null | null | Wrapper class (e.g. `card card-tabs`). Null = no wrapper. |

---

## Item structure

Each element in `items` can have:

| Key | Type | Required | Description |
|-----|------|----------|-------------|
| `label` | string | yes | Tab label (link text). |
| `content` | string | yes | Tab pane body content. |
| `icon` | string\|null | no | Font Awesome 5 icon class (e.g. `fas fa-home`). |
| `active` | bool | no | Whether this tab is active. First item is active if none set. |
| `id` | string\|null | no | Tab pane `id` (for `href="#id"`). Auto-generated if not set. |

---

## Default usage

```php
<?php use cinghie\adminlte3\widgets\NavTabs; ?>

<?= NavTabs::widget([
    'items' => [
        ['label' => 'Tab 1', 'content' => 'Content for tab 1.'],
        ['label' => 'Tab 2', 'content' => 'Content for tab 2.'],
    ],
]) ?>
```

Renders two tabs; the first is active by default.

---

## Custom usage

### With icons and active tab

```php
<?php use cinghie\adminlte3\widgets\NavTabs; ?>

<?= NavTabs::widget([
    'items' => [
        [
            'label' => 'Home',
            'content' => 'Home content here.',
            'icon' => 'fas fa-home',
            'active' => true,
        ],
        [
            'label' => 'Profile',
            'content' => 'Profile content here.',
            'icon' => 'fas fa-user',
        ],
        [
            'label' => 'Settings',
            'content' => 'Settings content here.',
            'icon' => 'fas fa-cog',
        ],
    ],
]) ?>
```

### Inside a card (wrapper)

```php
<?= NavTabs::widget([
    'wrapperClass' => 'card card-tabs',
    'items' => [
        ['label' => 'First', 'content' => 'First tab content.'],
        ['label' => 'Second', 'content' => 'Second tab content.', 'active' => true],
    ],
]) ?>
```

### Custom tab pane id

```php
<?= NavTabs::widget([
    'items' => [
        [
            'label' => 'Overview',
            'content' => 'Overview content.',
            'id' => 'tab-overview',
        ],
        [
            'label' => 'Details',
            'content' => 'Details content.',
            'id' => 'tab-details',
            'active' => true,
        ],
    ],
]) ?>
```

### Trusted HTML in content

Set `encodeContent` to `false` only when the content is trusted (e.g. server-generated HTML). Never use for user input.

```php
<?= NavTabs::widget([
    'items' => [
        [
            'label' => 'Rich',
            'content' => '<p>Pre-rendered <strong>HTML</strong>.</p>',
        ],
    ],
    'encodeContent' => false,
]) ?>
```

### Custom nav and tab content options

```php
<?= NavTabs::widget([
    'navOptions' => ['class' => 'nav nav-tabs nav-justified'],
    'tabContentOptions' => ['class' => 'tab-content p-3'],
    'items' => [
        ['label' => 'Tab 1', 'content' => 'Content 1.'],
        ['label' => 'Tab 2', 'content' => 'Content 2.'],
    ],
]) ?>
```

---

## Notes

- Tabs require Bootstrap 4 JavaScript (tabs plugin). AdminLTE 3 assets (e.g. `AdminLTEMinifyAsset`) include it.
- Use Font Awesome 5 icon classes for `icon` (e.g. `fas fa-*`, `far fa-*`).
- If no item has `active => true`, the first tab is active.
- Tab pane `id` values are auto-generated (e.g. `w0-tab-0`) when not set; use `id` in items for stable anchors or custom styling.
- Set `encodeLabels` or `encodeContent` to `false` only for trusted HTML; never for user-generated content.
