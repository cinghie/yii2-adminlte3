<?php

namespace cinghie\adminlte3\widgets;

use cinghie\adminlte3\AdminLTEDateTimeAsset;
use cinghie\adminlte3\AdminLTEDateTimeMinifyAsset;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;
use yii\widgets\InputWidget;

/**
 * Bootstrap 4 / AdminLTE 3 DateTimePicker backed by bundled Tempus Dominus.
 *
 * The widget reuses an already registered source/minified date-time bundle when
 * the host layout owns AdminLTE assets, then binds both the prepended icon and
 * input focus explicitly to the plugin `show()` command.
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
                'data-cinghie-datetime-toggle' => '1',
                'role' => 'button',
                'tabindex' => '0',
                'aria-label' => $this->toggleLabel,
            ]
        );

        $html = Html::tag('div', $addon . $input, [
            'class' => 'input-group date cinghie-adminlte3-datetimepicker',
            'id' => $wrapperId,
            'data-target-input' => 'nearest',
        ]);

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
        $selector = Json::encode('#' . $wrapperId);
        $config = Json::encode($pluginOptions);

        $view->registerCss('.bootstrap-datetimepicker-widget{z-index:1080!important}');
        $script = <<<JS
(function ($) {
    var selector = {$selector};
    var config = {$config};

    function initCinghieDateTimePicker() {
        var picker = $(selector);
        if (!picker.length || typeof $.fn.datetimepicker !== 'function') {
            return false;
        }
        if (!picker.data('cinghie-datetimepicker-ready')) {
            picker.datetimepicker(config);
            picker.data('cinghie-datetimepicker-ready', true);
        }

        picker.find('[data-cinghie-datetime-toggle]')
            .off('.cinghieDateTimePicker')
            .on('click.cinghieDateTimePicker keydown.cinghieDateTimePicker', function (event) {
                if (event.type === 'keydown' && event.key !== 'Enter' && event.key !== ' ') {
                    return;
                }
                event.preventDefault();
                picker.datetimepicker('show');
            });

        picker.find('.datetimepicker-input')
            .off('focus.cinghieDateTimePicker')
            .on('focus.cinghieDateTimePicker', function () {
                picker.datetimepicker('show');
            });
        return true;
    }

    if (!initCinghieDateTimePicker()) {
        window.setTimeout(initCinghieDateTimePicker, 0);
        $(window).one('load.cinghieDateTimePicker', initCinghieDateTimePicker);
    }
})(jQuery);
JS;
        $view->registerJs($script, View::POS_READY, 'cinghie-adminlte3-datetimepicker-' . $wrapperId);

        return $html;
    }
}
