# Breadcrumbs Widget

The **Breadcrumbs** widget extends Yii's base Breadcrumbs and renders navigation breadcrumbs with AdminLTE 3 / Bootstrap 4 markup: `<ol class="breadcrumb float-sm-right">` and `<li class="breadcrumb-item">` / `<li class="breadcrumb-item active">`.

**Reference:** [AdminLTE 3 – Breadcrumb](https://adminlte.io/docs/3.0/components/breadcrumb.html)

---

## Properties

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `tag` | string | `ol` | Container tag (AdminLTE 3 uses `ol`). |
| `options` | array | `['class' => 'breadcrumb float-sm-right']` | HTML attributes for the container. |
| `itemTemplate` | string | `"<li class=\"breadcrumb-item\">{link}</li>\n"` | Template for inactive items. |
| `activeItemTemplate` | string | `"<li class=\"breadcrumb-item active\" aria-current=\"page\">{link}</li>\n"` | Template for the active (last) item. |
| `links` | array | — | Breadcrumb items. See Yii Breadcrumbs. |
| `homeLink` | array\|false | — | Home link config or false to hide. |

---

## Usage

Usually used inside **ContentHeader** (which includes breadcrumbs). You can use Breadcrumbs alone:

```php
<?php use cinghie\adminlte3\widgets\Breadcrumbs; ?>

<?= Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    'homeLink' => [
        'label' => Yii::t('yii', 'Home'),
        'url' => Yii::$app->homeUrl,
    ],
]) ?>
```

With custom links:

```php
<?= Breadcrumbs::widget([
    'links' => [
        ['label' => 'Section', 'url' => ['/section/index']],
        ['label' => 'Page'],
    ],
]) ?>
```

---

## Notes

- The widget uses Bootstrap 4 breadcrumb classes (`breadcrumb-item`, `breadcrumb-item active`).
- ContentHeader already uses this Breadcrumbs widget; in most layouts you only set `$this->params['breadcrumbs']` and render ContentHeader.
