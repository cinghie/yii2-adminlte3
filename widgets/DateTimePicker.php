<?php

namespace cinghie\adminlte3\widgets;

use cinghie\adminlte3\AdminLTEDateTimeAsset;
use cinghie\adminlte3\AdminLTEDateTimeMinifyAsset;
use cinghie\adminlte3\assets\DateTimePickerWidgetAsset;
use cinghie\adminlte3\widgets\support\SafeHtml;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

/**
 * Bootstrap 4 / AdminLTE 3 DateTimePicker backed by bundled Tempus Dominus.
 *
 * The widget reuses an already registered source/minified date-time bundle when
 * the host layout owns AdminLTE assets. Package-owned behavior and presentation
 * live in an external widget asset; instance configuration is passed as encoded
 * JSON data so rendering does not require per-instance inline JavaScript/CSS.
 */
class DateTimePicker extends InputWidget
{
    /** @var string Moment/Tempus Dominus format. */
    public $format = 'YYYY-MM-DD HH:mm:ss';

    /** @var string Font Awesome icon class rendered before the input. */
    public $icon = 'far fa-calendar-alt';

    /** @var string Accessible label for the prepended picker trigger. */
    public $toggleLabel = 'Open date and time picker';

    /** @var array Tempus Dominus plugin options. */
    public $pluginOptions = [];

    public function run()
    {
        $view = $this->getView();
        if (isset($view->assetBundles[AdminLTEDateTimeAsset::class])) {
            $assetClass = AdminLTEDateTimeAsset::class;
        } elseif (isset($view->assetBundles[AdminLTEDateTimeMinifyAsset::class])) {
            $assetClass = AdminLTEDateTimeMinifyAsset::class;
        } else {
            $assetClass = YII_DEBUG ? AdminLTEDateTimeAsset::class : AdminLTEDateTimeMinifyAsset::class;
            $assetClass::register($view);
        }
        DateTimePickerWidgetAsset::register($view);

        $inputId = $this->options['id'] ?? Html::getInputId($this->model, $this->attribute);
        $wrapperId = $inputId . '-datetimepicker';

        $options = $this->options;
        $options['id'] = $inputId;
        $options['autocomplete'] = $options['autocomplete'] ?? 'off';
        $options['data-target'] = '#' . $wrapperId;
        Html::addCssClass($options, 'form-control datetimepicker-input');

        $input = $this->hasModel()
            ? Html::activeTextInput($this->model, $this->attribute, $options)
            : Html::textInput($this->name, $this->value, $options);

        $icon = SafeHtml::iconClass($this->icon, 'far fa-calendar-alt');
        $addon = Html::tag(
            'div',
            Html::tag('div', Html::tag('i', '', ['class' => $icon]), ['class' => 'input-group-text']),
            [
                'class' => 'input-group-prepend',
                'data-target' => '#' . $wrapperId,
                'data-toggle' => 'datetimepicker',
                'data-cinghie-datetime-toggle' => '1',
                'role' => 'button',
                'tabindex' => '0',
                'aria-label' => $this->toggleLabel,
            ]
        );

        $defaults = [
            'format' => $this->format,
            'allowInputToggle' => true,
            'icons' => [
                'time' => 'far fa-clock',
                'date' => 'far fa-calendar-alt',
                'up' => 'fas fa-arrow-up',
                'down' => 'fas fa-arrow-down',
                'previous' => 'fas fa-chevron-left',
                'next' => 'fas fa-chevron-right',
                'today' => 'far fa-calendar-check',
                'clear' => 'fas fa-trash',
                'close' => 'fas fa-times',
            ],
        ];
        $pluginOptions = array_replace_recursive($defaults, $this->pluginOptions);

        return Html::tag('div', $addon . $input, [
            'class' => 'input-group date cinghie-adminlte3-datetimepicker',
            'id' => $wrapperId,
            'data-target-input' => 'nearest',
            'data-cinghie-datetimepicker' => '1',
            'data-cinghie-datetime-options' => Json::htmlEncode($pluginOptions),
        ]);
    }
}
