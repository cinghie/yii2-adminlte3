<?php

namespace cinghie\adminlte3\widgets;

use cinghie\adminlte3\assets\CalendarWidgetAsset;
use yii\bootstrap4\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Renders a FullCalendar container with JSON-encoded event and option data.
 *
 * The widget deliberately keeps server-side responsibilities bounded: it
 * serializes data and optionally registers the package's FullCalendar assets,
 * while browser interaction and rendering remain owned by FullCalendar.
 */
class Calendar extends Widget
{
    /** @var array<int,array<string,mixed>> Calendar event definitions. */
    public $events = [];

    /** @var array<string,mixed> JSON-safe FullCalendar options. */
    public $calendarOptions = [];

    /** @var array HTML options for the calendar container. */
    public $options = [];

    /** @var bool Whether to register optional FullCalendar assets automatically. */
    public $registerAssets = true;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if ($this->registerAssets) {
            CalendarWidgetAsset::register($this->getView());
        }

        $htmlOptions = $this->options;
        $htmlOptions['id'] = $this->getId();
        $htmlOptions['data-cinghie-calendar'] = '1';
        $htmlOptions['data-cinghie-calendar-events'] = Json::encode($this->events);
        $htmlOptions['data-cinghie-calendar-options'] = Json::encode($this->calendarOptions);
        Html::addCssClass($htmlOptions, 'cinghie-calendar');

        return Html::tag('div', '', $htmlOptions);
    }
}
