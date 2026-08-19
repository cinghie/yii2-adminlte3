# Error404

`Error404` renders an AdminLTE-styled not-found page with encoded user-facing text and an accessible navigation action.

```php
use cinghie\adminlte3\widgets\Error404;

echo Error404::widget([
    'title' => 'Page not found',
    'message' => 'The requested record is no longer available.',
    'homeUrl' => ['/site/index'],
    'homeLabel' => 'Return to dashboard',
]);
```

`title`, `message`, and `homeLabel` are always HTML-encoded by the shared error-page view. The widget does not accept or render exception objects or stack traces.
