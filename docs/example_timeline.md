# Timeline Widget

The **Timeline** widget renders an AdminLTE 3 timeline: for each day, a time label and a list of items (icon, time, header with user/action, body with entity link). All dynamic text is HTML-encoded; icon class is sanitized. Optional `userNames` map (user_id => username) avoids N+1 queries when resolving usernames.

**Reference:** [AdminLTE 3 – Timeline](https://adminlte.io/docs/3.0/components/timeline.html)

---

## Properties

| Property | Type | Description |
|----------|------|-------------|
| `days` | array | List of day items; each element must have key `created_date` (e.g. `['created_date' => '2025-01-30']`). |
| `items` | array | List of log/item objects with at least: `created_date`, `created_time`, `created_by`, `action`, `entity_name`, `entity_model`, `entity_id`, `entity_url`, `data`, `icon`. |
| `userNames` | array | Optional map `user_id => username` to avoid N+1 queries. Example: `User::find()->select(['id','username'])->indexBy('id')->column()`. |

---

## Usage

```php
<?php use cinghie\adminlte3\widgets\Timeline; ?>

<?= Timeline::widget([
    'days' => [
        ['created_date' => '2025-01-30'],
        ['created_date' => '2025-01-29'],
    ],
    'items' => $items, // e.g. Loggers::find()->orderBy('created DESC')->all()
    'userNames' => $userNames, // optional: [1 => 'admin', 2 => 'user2']
]) ?>
```

With userNames from a search model (e.g. in logger module):

```php
$searchModel = new LoggersSearch();
$userNames = $searchModel->getUsersSelect2();

echo Timeline::widget([
    'days' => $days,
    'items' => $items,
    'userNames' => $userNames,
]);
```

---

## Notes

- Each item in `days` must be an array with key `created_date` (string date). The widget groups `items` by `created_date` and renders a time label per day.
- The Timeline widget has logic for entity_model (e.g. Order, Contact) to resolve links and labels; it may depend on application models (e.g. cinghie\commerce, cinghie\userextended). For a generic logger, ensure items have the expected attributes.
- Pass `userNames` when you have a list of user id => name to avoid one query per item for the username.
- Icon: use Font Awesome 5 classes (e.g. `fas fa-circle`). The widget sanitizes the icon string for safe use in class attributes.
