<?php

namespace cinghie\adminlte3\widgets;

use cinghie\adminlte3\assets\ColorPickerWidgetAsset;
use cinghie\adminlte3\widgets\support\SafeHtml;
use yii\helpers\Html;
use yii\widgets\InputWidget;

/**
 * Bootstrap 4/AdminLTE 3 color input with HEX text, preview and deferred palette.
 *
 * The submitted value is the text input. Suggested colors and the unrestricted
 * native color input remain hidden until the preview button is activated. The
 * widget keeps package-owned CSS/JavaScript in an external asset and uses
 * non-interactive canvases for visual chips/previews so arbitrary valid palette
 * values do not need dynamic inline style attributes.
 */
class ColorPicker extends InputWidget
{
    public const DEFAULT_PALETTE = [
        '#3c8dbc', '#00c0ef', '#00a65a', '#f39c12', '#f56954', '#d81b60', '#605ca8', '#001f3f',
        '#39cccc', '#01ff70', '#ffdc00', '#ff851b', '#dd4b39', '#b10dc9', '#111111', '#7f8c8d',
    ];

    /** @var string[] Suggested colors. Only six-digit HEX values are rendered. */
    public $palette = self::DEFAULT_PALETTE;

    /** @var string Font Awesome icon classes rendered before the text input. */
    public $iconClass = 'fas fa-paint-brush';

    /** @var string Accessible/user-facing label for the picker controls. */
    public $label = 'Color';

    public function run()
    {
        ColorPickerWidgetAsset::register($this->getView());

        $id = $this->options['id'] ?? ($this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->getId());
        $textOptions = $this->options;
        $textOptions['id'] = $id;
        $textOptions['maxlength'] = 32;
        $textOptions['placeholder'] = '#3c8dbc';
        Html::addCssClass($textOptions, 'form-control cinghie-color-value');

        $value = (string) ($this->hasModel() ? Html::getAttributeValue($this->model, $this->attribute) : $this->value);
        $nativeValue = preg_match('/^#[0-9a-f]{6}$/i', $value) ? strtolower($value) : '#3c8dbc';
        $pickerId = $id . '-native';
        $toggleId = $id . '-toggle';
        $paletteId = $id . '-palette';

        $swatches = '';
        foreach ($this->palette as $color) {
            if (!preg_match('/^#[0-9a-f]{6}$/i', (string) $color)) {
                continue;
            }
            $normalized = strtolower((string) $color);
            $selected = $normalized === $nativeValue;
            $chip = Html::tag('canvas', '', [
                'class' => 'cinghie-color-swatch-chip',
                'width' => 80,
                'height' => 30,
                'data-color' => $normalized,
                'aria-hidden' => 'true',
            ]);
            $swatches .= Html::button($chip, [
                'type' => 'button',
                'class' => 'cinghie-color-swatch' . ($selected ? ' is-selected' : ''),
                'title' => $normalized,
                'aria-label' => $normalized,
                'aria-pressed' => $selected ? 'true' : 'false',
                'data-color' => $normalized,
            ]);
        }

        $html = Html::beginTag('div', [
            'class' => 'cinghie-color-picker',
            'data-color-picker' => $id,
            'data-cinghie-color-picker' => '1',
        ]);
        $html .= Html::beginTag('div', ['class' => 'cinghie-color-row']);
        $iconClass = SafeHtml::iconClass($this->iconClass, 'fas fa-paint-brush');
        if ($iconClass !== '') {
            $html .= Html::tag(
                'span',
                Html::tag('i', '', ['class' => $iconClass]),
                ['class' => 'cinghie-color-addon', 'aria-hidden' => 'true']
            );
        }
        $html .= $this->hasModel()
            ? Html::activeTextInput($this->model, $this->attribute, $textOptions)
            : Html::textInput($this->name, $this->value, $textOptions);

        $preview = Html::tag('canvas', '', [
            'class' => 'cinghie-color-preview',
            'width' => 24,
            'height' => 20,
            'data-color' => $nativeValue,
            'aria-hidden' => 'true',
        ]);
        $html .= Html::button(
            $preview . Html::tag('span', '▾', ['class' => 'cinghie-color-chevron', 'aria-hidden' => 'true']),
            [
                'id' => $toggleId,
                'type' => 'button',
                'class' => 'btn btn-default cinghie-color-toggle',
                'title' => $this->label,
                'aria-label' => $this->label,
                'aria-haspopup' => 'true',
                'aria-expanded' => 'false',
                'aria-controls' => $paletteId,
            ]
        );
        $html .= Html::endTag('div');
        $html .= Html::beginTag('div', ['id' => $paletteId, 'class' => 'cinghie-color-popover', 'hidden' => true]);
        $html .= Html::tag('div', Html::encode($this->label), ['class' => 'cinghie-color-popover-title']);
        $html .= Html::tag('div', $swatches, ['class' => 'cinghie-color-palette']);
        $html .= Html::beginTag('label', ['class' => 'cinghie-color-custom']);
        $html .= Html::tag('span', Html::encode($this->label));
        $html .= Html::input('color', null, $nativeValue, [
            'id' => $pickerId,
            'class' => 'cinghie-color-native',
            'aria-label' => $this->label,
        ]);
        $html .= Html::endTag('label') . Html::endTag('div') . Html::endTag('div');

        return $html;
    }
}
