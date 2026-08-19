<?php

namespace cinghie\adminlte3\widgets;

use cinghie\adminlte3\assets\CalendarWidgetAsset;
use cinghie\adminlte3\widgets\support\SafeHtml;
use yii\bootstrap4\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Renders a FullCalendar container with JSON-encoded event and option data.
 *
 * The widget deliberately keeps server-side responsibilities bounded: it
 * serializes data and optionally registers the package's FullCalendar assets,
 * while browser interaction and rendering remain owned by FullCalendar.
 * By default the container is wrapped in the same primary card / p-0 body
 * structure used by the AdminLTE 3 Calendar example.
 */
class Calendar extends Widget
{
    /** @var array<int,array<string,mixed>> Calendar event definitions. */
    public $events = [];

    /** @var array<string,mixed> JSON-safe FullCalendar options. */
    public $calendarOptions = [];

    /** @var array HTML options for the calendar container. */
    public $options = [];

    /** @var bool Whether to wrap the calendar in the AdminLTE example card. */
    public $card = true;

    /** @var string|null Contextual type for the optional card wrapper. */
    public $cardType = Card::TYPE_PRIMARY;

    /** @var array HTML options for the optional outer card. */
    public $cardOptions = [];

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
        $htmlOptions['data-cinghie-calendar-events'] = Json::encode($this->normalizeEvents($this->events));
        $htmlOptions['data-cinghie-calendar-options'] = Json::encode($this->calendarOptions);
        Html::addCssClass($htmlOptions, 'cinghie-calendar');

        $calendar = Html::tag('div', '', $htmlOptions);
        if (!$this->card) {
            return $calendar;
        }

        return Card::widget([
            'type' => $this->cardType,
            'body' => $calendar,
            'encodeBody' => false,
            'bodyOptions' => ['class' => 'p-0'],
            'options' => $this->cardOptions,
        ]);
    }

    /**
     * Normalizes security-sensitive event fields before JSON serialization.
     *
     * FullCalendar renders event URLs as navigation targets. JSON/HTML encoding
     * protects the surrounding markup but does not validate the URL scheme, so
     * explicit event URLs must pass the same package policy as other links.
     *
     * @param array<int,array<string,mixed>> $events Calendar event definitions.
     * @return array<int,array<string,mixed>>
     */
    protected function normalizeEvents(array $events): array
    {
        foreach ($events as &$event) {
            if (array_key_exists('url', $event) && $event['url'] !== null && $event['url'] !== '') {
                $event['url'] = SafeHtml::linkUrl($event['url'], '#');
            }
        }
        unset($event);

        return $events;
    }
}
