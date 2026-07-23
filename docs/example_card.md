# Card Widget

The **Card** widget renders an [AdminLTE 3 card](https://adminlte.io/docs/3.1/components/cards.html): optional header (title, icon, tools), body, and optional footer.

Supports:

- `Card::widget([...])` with a `body` string
- `Card::begin([...])` / `Card::end()` to wrap view content
- Colors, outline, collapse / remove / maximize tools

For GridView inside the body and footer action buttons, use the **[Box](example_box.md)** widget instead.

---

## Properties

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `wrapperClass` | string\|null | `null` | Column wrapper (e.g. `col-md-6`). |
| `type` | string\|null | `null` | `primary`, `secondary`, `success`, `info`, `warning`, `danger`, `dark`, `light` (or `Card::TYPE_*`). |
| `outline` | bool | `false` | `card-outline card-{type}`. |
| `cardClass` | string\|null | `null` | Legacy full class string (e.g. `card-info`). Prefer `type` + `outline`. |
| `title` | string\|null | `null` | Header title. |
| `titleIcon` | string\|null | `null` | FA icon class (e.g. `fas fa-chart-pie`). |
| `encodeTitle` | bool | `true` | HTML-encode title. |
| `collapsible` | bool | `false` | Collapse tool. |
| `removable` | bool | `false` | Remove tool. |
| `maximizable` | bool | `false` | Maximize tool. |
| `collapsed` | bool | `false` | Start collapsed. |
| `headerTools` | string | `''` | Extra trusted HTML inside `.card-tools`. |
| `body` | string | `''` | Body when using `widget()` (ignored if begin/end has content). |
| `encodeBody` | bool | `true` | Encode `body` for `widget()`. |
| `footer` | string\|null | `null` | Footer text/HTML. |
| `encodeFooter` | bool | `true` | Encode footer. |
| `options` | array | `[]` | Attributes for `.card`. |
| `headerOptions` | array | `[]` | Attributes for `.card-header`. |
| `bodyOptions` | array | `[]` | Attributes for `.card-body`. |
| `footerOptions` | array | `[]` | Attributes for `.card-footer`. |

---

## Type constants

| Constant | Value |
|----------|--------|
| `Card::TYPE_PRIMARY` | `primary` |
| `Card::TYPE_SECONDARY` | `secondary` |
| `Card::TYPE_SUCCESS` | `success` |
| `Card::TYPE_INFO` | `info` |
| `Card::TYPE_WARNING` | `warning` |
| `Card::TYPE_DANGER` | `danger` |
| `Card::TYPE_DARK` | `dark` |
| `Card::TYPE_LIGHT` | `light` |

Legacy `Card::COLOR_*` / `Card::COLORS` (`card-info`, …) still work via `cardClass`.

---

## Usage

### Simple widget

```php
<?php use cinghie\adminlte3\widgets\Card; ?>

<?= Card::widget([
    'type' => Card::TYPE_INFO,
    'title' => 'Info',
    'titleIcon' => 'fas fa-info-circle',
    'body' => 'Card body content.',
    'footer' => 'Updated today',
]) ?>
```

### begin / end (preferred for view HTML)

```php
<?php use cinghie\adminlte3\widgets\Card; ?>

<?php Card::begin([
    'type' => Card::TYPE_PRIMARY,
    'outline' => true,
    'title' => 'Orders',
    'titleIcon' => 'fas fa-shopping-cart',
    'collapsible' => true,
    'maximizable' => true,
]); ?>
    <p>Any view markup here.</p>
<?php Card::end(); ?>
```

### In a Bootstrap row

```php
<div class="row">
    <?= Card::widget([
        'wrapperClass' => 'col-md-6',
        'type' => Card::TYPE_SUCCESS,
        'title' => 'Left',
        'body' => '…',
    ]) ?>
    <?= Card::widget([
        'wrapperClass' => 'col-md-6',
        'type' => Card::TYPE_WARNING,
        'outline' => true,
        'title' => 'Right',
        'body' => '…',
        'collapsible' => true,
        'removable' => true,
    ]) ?>
</div>
```

### Trusted HTML body

```php
<?= Card::widget([
    'title' => 'Rich',
    'body' => '<p>Trusted <strong>HTML</strong>.</p>',
    'encodeBody' => false,
]) ?>
```

---

## Notes

- Tools require AdminLTE’s card widgets JS (included with AdminLTE 3 assets).
- Set `encodeBody` / `encodeTitle` / `encodeFooter` to `false` only for trusted HTML.
- Use **Box** when you need GridView body + footer buttons.
