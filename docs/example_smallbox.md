# SmallBox Widget

The **SmallBox** widget renders an AdminLTE 3 small-box: a compact stat box with a main number, a label, an icon, and an optional footer link. Text is encoded and footer URLs are normalized through the package URL policy.

## Properties

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `wrapperClass` | string | `col-md-3 col-sm-6 col-12` | Bootstrap wrapper classes. |
| `bgClass` | string | `SmallBox::COLOR_INFO` | Background utility class. |
| `title` | string | `0` | Main value. |
| `subtitle` | string | `''` | Descriptive text. |
| `icon` | string | `fas fa-shopping-cart` | Font Awesome icon classes. |
| `url` | string\|array\|null | `null` | Canonical footer URL or Yii route array. |
| `link` | string\|array\|null | `null` | Deprecated alias for `url`. |
| `footerText` | string | `More info` | Footer label. |

When both `url` and deprecated `link` are supplied, `url` wins.

## Usage

```php
<?php use cinghie\adminlte3\widgets\SmallBox; ?>

<div class="row">
    <?= SmallBox::widget([
        'title' => '150',
        'subtitle' => 'New Orders',
        'icon' => 'fas fa-shopping-cart',
        'bgClass' => SmallBox::COLOR_INFO,
        'url' => ['/orders/index'],
        'footerText' => 'View orders',
    ]) ?>
</div>
```

Omit `url` to render the box without a footer link. The historical `link` property remains available for backward compatibility but is deprecated.

## Color constants

`SmallBox::COLOR_INFO`, `COLOR_SUCCESS`, `COLOR_WARNING`, and `COLOR_DANGER` map to the corresponding AdminLTE `bg-*` classes. `SmallBox::COLORS` remains available for iteration.
