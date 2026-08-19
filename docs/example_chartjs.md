# ChartJS

`ChartJS` renders a deterministic canvas and JSON-encodes chart data/configuration into HTML data attributes. By default it registers the package's optional minified Chart.js asset only on pages where the widget is used.

```php
use cinghie\adminlte3\widgets\ChartJS;

echo ChartJS::widget([
    'id' => 'orders-chart',
    'type' => 'line',
    'data' => [
        'labels' => ['Mon', 'Tue', 'Wed'],
        'datasets' => [[
            'label' => 'Orders',
            'data' => [12, 18, 15],
        ]],
    ],
    'chartOptions' => [
        'legend' => ['display' => false],
    ],
]);
```

The default canvas id is `<widget-id>-canvas`; set `canvasId` when a specific stable id is required. `responsive` defaults to `true` and `maintainAspectRatio` to `false`, and both can be overridden in `chartOptions`.

`data` and `chartOptions` must be JSON-safe PHP arrays/scalars. Executable JavaScript callbacks are intentionally outside this widget's server-side API. Set `registerAssets` to `false` when the application owns Chart.js registration. Source and minified vendor bundles are available as `AdminLTEChartJSAsset` and `AdminLTEChartJSMinifyAsset`.
