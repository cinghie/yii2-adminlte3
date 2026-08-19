# Error404

`Error404` renders an AdminLTE-styled not-found page with encoded user-facing text and an accessible navigation action. Its structure mirrors the AdminLTE 3 error example: warning headline, warning triangle icon, `.error-content`, explanatory text, and an ordinary dashboard link rather than a visually unrelated action button.

```php
use cinghie\adminlte3\widgets\Error404;

echo Error404::widget([
    'title' => 'Page not found',
    'message' => 'The requested record is no longer available.',
    'homeUrl' => ['/site/index'],
    'homeLabel' => 'Return to dashboard',
]);
```

`title`, `message`, and `homeLabel` are always HTML-encoded by the shared error-page view. `homeUrl` is normalized through the package safe-link policy, so executable or otherwise unsupported explicit schemes fall back to `/`. The widget does not accept or render exception objects or stack traces.
