<?php

namespace cinghie\adminlte3\widgets;

use cinghie\adminlte3\AdminLTEDateTimeAsset;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

/**
 * Bootstrap 4 / AdminLTE 3 DateTimePicker backed by bundled Tempus Dominus.
 *
 * This avoids loading a second jQuery `datetimepicker` implementation on pages
 * that already use the AdminLTE 3 aggregate assets.
 */
class DateTimePicker extends InputWidget
{
    /** @var string Moment/Tempus Dominus format. */
    public $format = 'YYYY-MM-DD HH:mm:ss';

    /** @var string Font Awesome icon class rendered before the input. */
    public $icon = 'far fa-calendar-alt';

    /** @var array Tempus Dominus plugin options. */
    public $pluginOptions = [];

    public function run()
    {
        $view = $this->getView();
        AdminLTEDateTimeAsset::register($view);

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

        $addon = Html::tag(
            'div',
            Html::tag('div', Html::tag('i', '', ['class' => $this->icon]), ['class' => 'input-group-text']),
            [
                'class' => 'input-group-prepend',
                'data-target' => '#' . $wrapperId,
                'data-toggle' => 'datetimepicker',
                'role' => 'button',
                'aria-label' => 'Open date and time picker',
            ]
        );

        $html = Html::tag('div', $addon . $input, [
            'class' => 'input-group date cinghie-adminlte3-datetimepicker',
            'id' => $wrapperId,
            'data-target-input' => 'nearest',
        ]);

        $pluginOptions = array_merge(['format' => $this->format], $this->pluginOptions);
        $selector = Json::htmlEncode('#' . $wrapperId);
        $config = Json::htmlEncode($pluginOptions);
        $view->registerJs("jQuery({$selector}).datetimepicker({$config});");

        return $html;
    }
}
