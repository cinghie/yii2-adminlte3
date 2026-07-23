# DetailView Widget

The **DetailView** widget extends [Kartik DetailView](https://github.com/kartik-v/yii2-detail-view) and styles the panel as an AdminLTE 3 **card** (Bootstrap 4), following the same approach as the AdminLTE 3 GridView.

**Reference:** [AdminLTE 3 — Cards](https://adminlte.io/docs/3.1/components/cards.html)

---

## Behaviour

- Forces Kartik `bsVersion = 4`
- Maps legacy panel types (`info`, `success`, …) to `card-outline card-{type}`
- Normalizes heading markup (`panel-title` → `card-title`, Font Awesome 4 → 5 prefixes)
- Registers light CSS so card headers align like AdminLTE

---

## Usage

```php
<?php
use cinghie\adminlte3\widgets\DetailView;

echo DetailView::widget([
    'model' => $model,
    'mode' => DetailView::MODE_VIEW,
    'enableEditMode' => false,
    'panel' => [
        'heading' => 'Details',
        'type' => DetailView::TYPE_INFO,
    ],
    'attributes' => [
        'id',
        'name',
    ],
]);
?>
```

In **yii2-crm**, prefer `cinghie\crm\widgets\DetailView`, which delegates to this widget when the CRM module uses `bootstrap4`, and falls back to Kartik (BS3) otherwise.
