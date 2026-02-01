# DataColumn (GridView)

**DataColumn** is a column class for use inside the AdminLTE 3 **GridView** widget. It extends Kartik DataColumn and adds sorting classes to the header (sorting, sorting_asc, sorting_desc) without mutating the original headerOptions. Used automatically when you use `cinghie\adminlte3\widgets\GridView` (it sets `dataColumnClass` to `DataColumn::class`).

---

## Usage

You do not need to reference DataColumn directly in most cases. The GridView widget uses it by default for data columns:

```php
<?php use cinghie\adminlte3\widgets\GridView; ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        'id',
        'name',
        'email',
        ['class' => 'yii\grid\ActionColumn'],
    ],
]) ?>
```

To explicitly use the AdminLTE 3 DataColumn for a column (e.g. for custom attribute with sorting):

```php
'columns' => [
    [
        'class' => \cinghie\adminlte3\widgets\DataColumn::class,
        'attribute' => 'name',
        'label' => 'Name',
    ],
],
```

---

## Notes

- GridView already sets `dataColumnClass` to `DataColumn::class`, so all attribute columns use this class by default.
- DataColumn adds Bootstrap 4 / AdminLTE 3 compatible sorting classes to the `<th>` header.
- For full GridView options see [GridView](example_gridview.md).
