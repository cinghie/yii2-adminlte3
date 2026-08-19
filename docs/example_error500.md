# Error500

`Error500` renders an AdminLTE-styled server-error page with a generic safe default message and an accessible navigation action.

```php
use cinghie\adminlte3\widgets\Error500;

echo Error500::widget([
    'homeUrl' => ['/site/index'],
    'homeLabel' => 'Return to dashboard',
]);
```

The default output does not expose exception messages, file paths, stack traces, or debug context. Applications may replace the user-facing `title` or `message`, but those values are always HTML-encoded by the shared view. Keep detailed diagnostics in server-side logs rather than public error markup.
