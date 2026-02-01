# Card Widget

The **Card** widget renders an AdminLTE 3 card: optional header (title + icon), body content, and optional footer. All text is HTML-encoded by default; class and icon are sanitized. Use it inside a Bootstrap row when using `wrapperClass`.

**Reference:** [AdminLTE 3 – Cards](https://adminlte.io/docs/3.0/components/cards.html)

---

## Properties

| Property       | Type     | Default | Description |
|----------------|----------|---------|-------------|
| `wrapperClass` | string\|null | null | Wrapper column class (e.g. `col-md-6`). Null = no wrapper div. |
| `cardClass`    | string\|null | null | Card type: `card-info`, `card-success`, `card-warning`, `card-danger`, `card-primary`, or outline variants (e.g. `card-outline card-info`). Use `Card::COLOR_*` constants. |
| `title`        | string\|null | null | Header title. Null or empty = no header. |
| `titleIcon`    | string\|null | null | Header icon class (e.g. `fas fa-chart-pie`). Null = no icon. |
| `body`         | string   | `''`   | Body content. |
| `footer`       | string\|null | null | Footer content. Null or empty = no footer. |
| `encodeBody`   | bool     | true   | Whether to HTML-encode body (XSS prevention). |
| `encodeTitle`  | bool     | true   | Whether to HTML-encode title. |
| `encodeFooter` | bool     | true   | Whether to HTML-encode footer. |
| `options`      | array    | []     | HTML options for the card container. |
| `bodyOptions`  | array    | []     | HTML options for the card body. |

---

## Color constants

The widget defines four standard card type classes (same style as SmallBox/InfoBox):

| Constant | Value | Description |
|----------|--------|-------------|
| `Card::COLOR_INFO` | `card-info` | Blue |
| `Card::COLOR_SUCCESS` | `card-success` | Green |
| `Card::COLOR_WARNING` | `card-warning` | Yellow/orange |
| `Card::COLOR_DANGER` | `card-danger` | Red |

All four are available in **`Card::COLORS`** (`'info' => 'card-info'`, etc.) for iteration or dropdowns.

```php
use cinghie\adminlte3\widgets\Card;

Card::widget([
    'cardClass' => Card::COLOR_SUCCESS,
    'title' => 'My Card',
    'body' => 'Content here.',
]);

foreach (Card::COLORS as $name => $cardClass) {
    echo Card::widget([
        'cardClass' => $cardClass,
        'title' => ucfirst($name),
        'body' => 'Content for ' . $name,
    ]);
}
```

You can use any valid AdminLTE card class (e.g. `card-primary`, `card-outline card-info`).

---

## Default usage

```php
<?php use cinghie\adminlte3\widgets\Card; ?>

<?= Card::widget([
    'body' => 'Card body content.',
]) ?>
```

Renders a card with no header, no footer, and the given body (encoded by default).

---

## Custom usage

### With header (title + icon)

```php
<?php use cinghie\adminlte3\widgets\Card; ?>

<div class="row">
    <?= Card::widget([
        'wrapperClass' => 'col-md-6',
        'title' => 'Card Title',
        'titleIcon' => 'fas fa-chart-pie',
        'body' => 'Body content goes here.',
    ]) ?>
</div>
```

### With footer

```php
<?= Card::widget([
    'title' => 'Statistics',
    'titleIcon' => 'fas fa-info-circle',
    'body' => 'Some stats or description.',
    'footer' => 'Last updated today',
]) ?>
```

### With color type (using constants)

```php
<div class="row">
    <?= Card::widget([
        'wrapperClass' => 'col-md-4',
        'cardClass' => Card::COLOR_INFO,
        'title' => 'Info Card',
        'titleIcon' => 'fas fa-info-circle',
        'body' => 'This is an info-style card.',
    ]) ?>
    <?= Card::widget([
        'wrapperClass' => 'col-md-4',
        'cardClass' => Card::COLOR_SUCCESS,
        'title' => 'Success Card',
        'titleIcon' => 'fas fa-check',
        'body' => 'This is a success-style card.',
    ]) ?>
    <?= Card::widget([
        'wrapperClass' => 'col-md-4',
        'cardClass' => Card::COLOR_WARNING,
        'title' => 'Warning Card',
        'titleIcon' => 'fas fa-exclamation-triangle',
        'body' => 'This is a warning-style card.',
    ]) ?>
</div>
```

### Outline style

```php
<?= Card::widget([
    'cardClass' => 'card-outline card-info',
    'title' => 'Outline Card',
    'body' => 'Card with outline style.',
]) ?>
```

### Trusted HTML in body

Set `encodeBody` to `false` only when the body is trusted (e.g. server-generated HTML). Never use for user input.

```php
<?= Card::widget([
    'title' => 'Rich content',
    'body' => '<p>Pre-rendered <strong>HTML</strong>.</p>',
    'encodeBody' => false,
]) ?>
```

### Custom options

```php
<?= Card::widget([
    'title' => 'Custom card',
    'body' => 'Content.',
    'options' => ['id' => 'my-card'],
    'bodyOptions' => ['class' => 'p-4'],
]) ?>
```

---

## Notes

- Wrap in `<div class="row">` when using `wrapperClass` (e.g. `col-md-6`).
- Use Font Awesome 5 icon classes for `titleIcon` (e.g. `fas fa-*`, `far fa-*`).
- For the four standard card types, use `Card::COLOR_INFO`, `Card::COLOR_SUCCESS`, `Card::COLOR_WARNING`, and `Card::COLOR_DANGER` (or iterate over `Card::COLORS`).
- Set `encodeBody` (or `encodeTitle` / `encodeFooter`) to `false` only for trusted HTML; never for user-generated content.
- For more complex cards (GridView in body, footer buttons, collapsible/removable tools), use the **Box** widget instead.
