# InfoBox Widget

The **InfoBox** widget renders an AdminLTE 3 info-box: a horizontal stat box with an icon on the left, label and number on the right, and an optional progress bar. All text is HTML-encoded; class and icon are sanitized. Use it inside a Bootstrap row.

**Reference:** [AdminLTE 3 – Boxes](https://adminlte.io/docs/3.0/components/boxes.html)

---

## Properties

| Property             | Type     | Default                 | Description |
|----------------------|----------|-------------------------|-------------|
| `wrapperClass`       | string   | `col-md-3 col-sm-6 col-12` | Wrapper column class (Bootstrap grid). |
| `iconBgClass`        | string   | `InfoBox::COLOR_INFO`   | Icon container background. Use `InfoBox::COLOR_*` constants or any AdminLTE class (e.g. `bg-primary`). |
| `text`               | string   | `''`                    | Label (info-box-text). |
| `number`             | string   | `0`                     | Main number/stat (info-box-number). |
| `icon`               | string   | `fas fa-envelope`       | Font Awesome 5 icon class (e.g. `far fa-envelope`). |
| `progress`           | int\|null| null                   | Progress bar value 0–100. Null = no progress bar. |
| `progressDescription`| string\|null | null               | Text below the progress bar (e.g. "70% Increase in 30 Days"). Ignored if `progress` is null. |

---

## Color constants

The widget defines four standard icon background classes (same as SmallBox):

| Constant | Value | Description |
|----------|--------|-------------|
| `InfoBox::COLOR_INFO` | `bg-info` | Blue |
| `InfoBox::COLOR_SUCCESS` | `bg-success` | Green |
| `InfoBox::COLOR_WARNING` | `bg-warning` | Yellow/orange |
| `InfoBox::COLOR_DANGER` | `bg-danger` | Red |

All four are available in **`InfoBox::COLORS`** (`'info' => 'bg-info'`, etc.) for iteration or dropdowns.

```php
use cinghie\adminlte3\widgets\InfoBox;

InfoBox::widget([
    'iconBgClass' => InfoBox::COLOR_SUCCESS,
    'text' => 'Bookmarks',
    'number' => '410',
    'icon' => 'fas fa-bookmark',
]);

foreach (InfoBox::COLORS as $name => $bgClass) {
    echo InfoBox::widget([
        'iconBgClass' => $bgClass,
        'text' => ucfirst($name),
        'number' => '0',
        'icon' => 'fas fa-circle',
    ]);
}
```

---

## Default usage

```php
<?php use cinghie\adminlte3\widgets\InfoBox; ?>

<div class="row">
    <?= InfoBox::widget() ?>
</div>
```

Renders an info-box with default text empty, number `0`, icon `fas fa-envelope`, and no progress bar.

---

## Custom usage

### Basic info-box

```php
<?php use cinghie\adminlte3\widgets\InfoBox; ?>

<div class="row">
    <?= InfoBox::widget([
        'text' => 'Messages',
        'number' => '1,410',
        'icon' => 'far fa-envelope',
        'iconBgClass' => InfoBox::COLOR_INFO,
    ]) ?>
</div>
```

### With progress bar

```php
<?= InfoBox::widget([
    'text' => 'Bookmarks',
    'number' => '410',
    'icon' => 'fas fa-bookmark',
    'iconBgClass' => InfoBox::COLOR_SUCCESS,
    'progress' => 70,
    'progressDescription' => '70% Increase in 30 Days',
]) ?>
```

### Multiple info-boxes in a row (using color constants)

```php
<div class="row">
    <?= InfoBox::widget([
        'text' => 'Messages',
        'number' => '1,410',
        'icon' => 'far fa-envelope',
        'iconBgClass' => InfoBox::COLOR_INFO,
    ]) ?>
    <?= InfoBox::widget([
        'text' => 'Bookmarks',
        'number' => '410',
        'icon' => 'fas fa-bookmark',
        'iconBgClass' => InfoBox::COLOR_SUCCESS,
    ]) ?>
    <?= InfoBox::widget([
        'text' => 'Uploads',
        'number' => '13,648',
        'icon' => 'fas fa-upload',
        'iconBgClass' => InfoBox::COLOR_WARNING,
    ]) ?>
    <?= InfoBox::widget([
        'text' => 'Downloads',
        'number' => '9,143',
        'icon' => 'fas fa-download',
        'iconBgClass' => InfoBox::COLOR_DANGER,
    ]) ?>
</div>
```

### Custom wrapper column

```php
<?= InfoBox::widget([
    'wrapperClass' => 'col-lg-3 col-6',
    'text' => 'Tickets',
    'number' => '12',
    'icon' => 'fas fa-ticket-alt',
    'iconBgClass' => InfoBox::COLOR_INFO,
]) ?>
```

---

## Notes

- Always wrap the widget in a `<div class="row">` when using the default or custom column classes.
- Use Font Awesome 5 icon classes (e.g. `fas fa-*`, `far fa-*`, `fab fa-*`).
- For the four standard icon colors, use `InfoBox::COLOR_INFO`, `InfoBox::COLOR_SUCCESS`, `InfoBox::COLOR_WARNING`, and `InfoBox::COLOR_DANGER` (or iterate over `InfoBox::COLORS`).
- `progress` is clamped to 0–100. Set `progress` to `null` or omit it to hide the progress bar.
- `progressDescription` is only shown when `progress` is set.
