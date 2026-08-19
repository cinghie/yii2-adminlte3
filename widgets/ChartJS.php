<?php

namespace cinghie\adminlte3\widgets;

use cinghie\adminlte3\assets\ChartJSWidgetAsset;
use cinghie\adminlte3\widgets\support\SafeHtml;
use yii\bootstrap4\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Renders a Chart.js canvas with JSON-safe data and configuration.
 *
 * The widget only serializes chart configuration and optionally registers the
 * package's Chart.js assets. It does not accept executable JavaScript callbacks
 * through its data/configuration API. By default it follows the AdminLTE 3
 * ChartJS example structure: contextual card, card header/tools, card body,
 * `.chart` wrapper, and a bounded 250px canvas presentation.
 */
class ChartJS extends Widget
{
    /** @var string Chart.js chart type. */
    public $type = 'line';

    /** @var array<string,mixed> JSON-safe Chart.js data. */
    public $data = [];

    /** @var array<string,mixed> JSON-safe Chart.js options. */
    public $chartOptions = [];

    /** @var string|null Explicit canvas identifier; defaults to `<widget-id>-canvas`. */
    public $canvasId;

    /** @var array HTML options for the chart container. */
    public $options = [];

    /** @var array HTML options for the canvas element. */
    public $canvasOptions = [];

    /** @var bool Whether to wrap the chart in an AdminLTE card. */
    public $card = true;

    /** @var string|null Optional card title; defaults to the generic AdminLTE-style label. */
    public $title = 'Chart';

    /** @var string|null Contextual type for the optional card wrapper. */
    public $cardType = Card::TYPE_PRIMARY;

    /** @var bool Whether to expose the standard AdminLTE collapse tool. */
    public $collapsible = true;

    /** @var array HTML options for the optional outer card. */
    public $cardOptions = [];

    /** @var bool Whether to register optional Chart.js assets automatically. */
    public $registerAssets = true;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if ($this->registerAssets) {
            ChartJSWidgetAsset::register($this->getView());
        }

        $canvasId = $this->normalizeId($this->canvasId ?: $this->getId() . '-canvas');
        $chartOptions = array_merge([
            'responsive' => true,
            'maintainAspectRatio' => false,
        ], $this->chartOptions);

        $canvasOptions = $this->canvasOptions;
        $canvasOptions['id'] = $canvasId;
        Html::addCssClass($canvasOptions, 'cinghie-chartjs-canvas');
        $canvas = Html::tag('canvas', '', $canvasOptions);

        $htmlOptions = $this->options;
        $htmlOptions['id'] = $this->getId();
        $htmlOptions['data-cinghie-chartjs'] = '1';
        $htmlOptions['data-cinghie-chartjs-canvas'] = $canvasId;
        $htmlOptions['data-cinghie-chartjs-type'] = $this->normalizeType($this->type);
        $htmlOptions['data-cinghie-chartjs-data'] = Json::encode($this->data);
        $htmlOptions['data-cinghie-chartjs-options'] = Json::encode($chartOptions);
        Html::addCssClass($htmlOptions, 'chart cinghie-chartjs');

        $chart = Html::tag('div', $canvas, $htmlOptions);
        if (!$this->card) {
            return $chart;
        }

        return Card::widget([
            'title' => $this->title,
            'type' => $this->cardType,
            'collapsible' => $this->collapsible,
            'body' => $chart,
            'encodeBody' => false,
            'options' => $this->cardOptions,
        ]);
    }

    /**
     * @param string $type Candidate Chart.js type.
     * @return string
     */
    protected function normalizeType($type): string
    {
        $types = [
            'bar' => 'bar',
            'bubble' => 'bubble',
            'doughnut' => 'doughnut',
            'horizontalbar' => 'horizontalBar',
            'line' => 'line',
            'pie' => 'pie',
            'polararea' => 'polarArea',
            'radar' => 'radar',
            'scatter' => 'scatter',
        ];
        $normalized = strtolower(trim((string) $type));

        return $types[$normalized] ?? 'line';
    }

    /**
     * @param string $id Candidate DOM id.
     * @return string
     */
    protected function normalizeId($id): string
    {
        $id = preg_replace('/[^A-Za-z0-9_\-:.]/', '-', (string) $id);
        $id = trim((string) $id, '-');

        return $id !== '' ? $id : SafeHtml::cssClass($this->getId() . '-canvas');
    }
}
