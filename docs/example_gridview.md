# GridView Widget

The **GridView** widget extends Kartik GridView and wraps the grid in an AdminLTE 3 card (card-body, card-footer). It uses Bootstrap 4 table classes and supports an optional card header. Compatible with Kartik grid options (dataProvider, columns, pjax, etc.).

**Reference:** [AdminLTE 3 – Cards](https://adminlte.io/docs/3.0/components/cards.html)

---

## Main properties (AdminLTE 3 specific)

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `cardClass` | string | `''` | Extra class for the card wrapper (e.g. `card-primary`). |
| `cardTitle` | string\|null | null | Optional card header title (HTML-encoded). |
| `boxClass` | string | `''` | Deprecated; use `cardClass`. Kept for backward compatibility. |
| `tableOptions` | array | `['class' => 'table table-sm']` | Options for the table tag (Bootstrap 4). |
| `dataColumnClass` | string | `DataColumn::class` | Column class (AdminLTE/Bootstrap 4 compatible). |
| `bordered` | bool | true | Table borders. |
| `condensed` | bool | true | Compact table (adds `table-sm`). |
| `striped` | bool | true | Striped rows. |
| `hover` | bool | true | Row hover. |
| `layout` | string\|null | null | Layout template; if null, set in init() to card-body/card-footer (and optional card-header). |
| `pjax` | bool | true | Whether to wrap in Pjax. |
| `pjaxSettings` | array | `['neverTimeout' => true]` | Pjax options. |

All other Kartik GridView properties (dataProvider, columns, filterModel, etc.) are supported.

---

## Usage

```php
<?php use cinghie\adminlte3\widgets\GridView; ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        'id',
        'name',
        ['class' => 'yii\grid\ActionColumn'],
    ],
    'cardTitle' => 'Items list',
    'cardClass' => 'card-primary',
    'pjax' => true,
]) ?>
```

Without card header:

```php
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [...],
]) ?>
```

---

## Notes

- The grid is wrapped in `<div class="card">` with optional `card-header`, `card-body` (items), and `card-footer` (summary + pager).
- Uses Bootstrap 4 table classes (`table-sm` instead of `table-condensed`).
- Requires Kartik yii2-grid. Pjax requires the yii2-pjax asset (or a local fallback).
