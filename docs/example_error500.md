# Error500

`Error500` renders an AdminLTE-styled server-error page with a generic safe default message and an accessible navigation action. Its structure mirrors the AdminLTE 3 error example: danger headline, danger triangle icon, `.error-content`, explanatory text, and an ordinary dashboard link.

```php
use cinghie\adminlte3\widgets\Error500;

echo Error500::widget([
    'homeUrl' => ['/site/index'],
    'homeLabel' => 'Return to dashboard',
]);
```

The default output does not expose exception messages, file paths, stack traces, or debug context. Applications may replace the user-facing `title` or `message`, but those values are always HTML-encoded by the shared view. `homeUrl` is normalized through the package safe-link policy, so executable or unsupported explicit schemes fall back to `/`. Keep detailed diagnostics in server-side logs rather than public error markup.
