# SmallBox Widget

The **SmallBox** widget renders an AdminLTE 3 small-box: a compact stat box with a main number (h3), a label (p), an icon, and an optional footer link. All text is HTML-encoded; the footer link URL is validated (no `javascript:` or `data:`). Use it inside a Bootstrap row.

**Reference:** [AdminLTE 3 – Boxes](https://adminlte.io/docs/3.0/components/boxes.html)

---

## Properties

| Property        | Type           | Default                | Description |
|----------------|----------------|------------------------|-------------|
| `wrapperClass` | string         | `col-md-3 col-sm-6 col-12` | Wrapper column class (Bootstrap grid). |
| `bgClass`      | string         | `SmallBox::COLOR_INFO` | Small-box background. Use `SmallBox::COLOR_*` constants (info, success, warning, danger) or any AdminLTE class (e.g. `bg-primary`, `bg-gradient-info`). |
| `title`        | string         | `0`                    | Main number or stat (rendered in `<h3>`). |
| `subtitle`     | string         | `''`                   | Label below the title (rendered in `<p>`). |
| `icon`         | string         | `fas fa-shopping-cart`  | Font Awesome 5 icon class (e.g. `fas fa-chart-line`). |
| `link`         | string\|array\|null | null              | Footer link URL. Use a string, a Yii route array (e.g. `['/site/index']`), or `null`/empty to hide the footer link. |
| `footerText`   | string         | `More info`            | Text for the footer link. Ignored if `link` is empty. |

---

## Color constants

The widget defines four standard background classes so you can use constants instead of strings:

| Constant | Value | Description |
|----------|--------|-------------|
| `SmallBox::COLOR_INFO` | `bg-info` | Blue |
| `SmallBox::COLOR_SUCCESS` | `bg-success` | Green |
| `SmallBox::COLOR_WARNING` | `bg-warning` | Yellow/orange |
| `SmallBox::COLOR_DANGER` | `bg-danger` | Red |

All four are also available in the **`SmallBox::COLORS`** array (`'info' => 'bg-info'`, `'success' => 'bg-success'`, etc.) for iteration or dropdowns.

```php
use cinghie\adminlte3\widgets\SmallBox;

// Single constant
SmallBox::widget([
    'bgClass' => SmallBox::COLOR_SUCCESS,
    'title' => '44',
    'subtitle' => 'User Registrations',
    'icon' => 'fas fa-user-plus',
]);

// All four via COLORS
foreach (SmallBox::COLORS as $name => $bgClass) {
    echo SmallBox::widget([
        'bgClass' => $bgClass,
        'title' => '0',
        'subtitle' => ucfirst($name),
        'icon' => 'fas fa-circle',
    ]);
}
```

You can still use any valid AdminLTE class for `bgClass` (e.g. `bg-primary`, `bg-secondary`, `bg-gradient-info`).

---

## Default usage

```php
<?php use cinghie\adminlte3\widgets\SmallBox; ?>

<div class="row">
    <?= SmallBox::widget() ?>
</div>
```

Renders a small-box with default title `0`, subtitle empty, icon `fas fa-shopping-cart`, background `bg-info`, and a “More info” footer link to `#`.

---

## Custom usage

### With footer link (route array)

```php
<?php use cinghie\adminlte3\widgets\SmallBox; ?>

<div class="row">
    <?= SmallBox::widget([
        'title' => '150',
        'subtitle' => 'New Orders',
        'icon' => 'fas fa-shopping-cart',
        'bgClass' => 'bg-info',
        'link' => ['/orders/index'],
        'footerText' => 'View orders',
    ]) ?>
</div>
```

### Without footer link

Omit `link` or set it to `null` / `''` to render the box without a footer link.

```php
<?= SmallBox::widget([
    'title' => '44',
    'subtitle' => 'User Registrations',
    'icon' => 'fas fa-user-plus',
    'bgClass' => 'bg-success',
]) ?>
```

### Multiple small-boxes in a row (using color constants)

```php
<div class="row">
    <?= SmallBox::widget([
        'title' => '150',
        'subtitle' => 'New Orders',
        'icon' => 'fas fa-shopping-cart',
        'bgClass' => SmallBox::COLOR_INFO,
        'link' => ['/orders/index'],
    ]) ?>
    <?= SmallBox::widget([
        'title' => '53',
        'subtitle' => 'Bounce Rate',
        'icon' => 'fas fa-chart-line',
        'bgClass' => SmallBox::COLOR_SUCCESS,
        'link' => ['/stats/bounce'],
        'footerText' => 'View report',
    ]) ?>
    <?= SmallBox::widget([
        'title' => '44',
        'subtitle' => 'User Registrations',
        'icon' => 'fas fa-user-plus',
        'bgClass' => SmallBox::COLOR_WARNING,
        'link' => ['/user/index'],
    ]) ?>
    <?= SmallBox::widget([
        'title' => '65',
        'subtitle' => 'Unique Visitors',
        'icon' => 'fas fa-users',
        'bgClass' => SmallBox::COLOR_DANGER,
    ]) ?>
</div>
```

### Custom wrapper column and gradient

```php
<?= SmallBox::widget([
    'wrapperClass' => 'col-lg-3 col-6',
    'title' => '12',
    'subtitle' => 'Tickets',
    'icon' => 'fas fa-ticket-alt',
    'bgClass' => 'bg-gradient-primary',
    'link' => '/support/tickets',
    'footerText' => 'Open tickets',
]) ?>
```

---

## Notes

- Always wrap the widget in a `<div class="row">` (or equivalent Bootstrap row) when using the default or custom column classes.
- Use Font Awesome 5 icon classes (e.g. `fas fa-*`, `far fa-*`, `fab fa-*`). The footer arrow icon is `fas fa-arrow-circle-right`.
- The footer link is only rendered when `link` is set and non-empty. The URL is validated; dangerous schemes (`javascript:`, `data:`, `vbscript:`) are replaced with `#`.
- For the four standard colors, prefer `SmallBox::COLOR_INFO`, `SmallBox::COLOR_SUCCESS`, `SmallBox::COLOR_WARNING`, and `SmallBox::COLOR_DANGER` (or iterate over `SmallBox::COLORS`).
