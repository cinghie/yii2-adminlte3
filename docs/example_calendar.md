# Calendar

`Calendar` renders a FullCalendar container and JSON-encodes event data and configuration into HTML data attributes. By default it registers the package's optional minified FullCalendar asset set only on pages where the widget is used.

```php
use cinghie\adminlte3\widgets\Calendar;

echo Calendar::widget([
    'id' => 'project-calendar',
    'events' => [
        [
            'title' => 'Release review',
            'start' => '2026-08-21T10:00:00',
            'end' => '2026-08-21T11:00:00',
        ],
    ],
    'calendarOptions' => [
        'height' => 'auto',
        'editable' => false,
    ],
]);
```

`events` and `calendarOptions` must be JSON-safe PHP arrays/scalars. Executable JavaScript callbacks are intentionally outside this widget's server-side API; add application-owned JavaScript when callback behavior is required.

Set `registerAssets` to `false` when the application registers FullCalendar itself. Source and minified vendor bundles are available as `AdminLTECalendarAsset` and `AdminLTECalendarMinifyAsset`.
