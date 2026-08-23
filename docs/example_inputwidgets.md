# ColorPicker, DatePicker and DateTimePicker

The AdminLTE 3 integration owns reusable Bootstrap 4 input widgets so feature modules do not need to duplicate presentation code.

```php
use cinghie\adminlte3\widgets\ColorPicker;
use cinghie\adminlte3\widgets\DatePicker;
use cinghie\adminlte3\widgets\DateTimePicker;

$form->field($model, 'color')->widget(ColorPicker::class, [
    'label' => 'Color',
]);

$form->field($model, 'date')->widget(DatePicker::class);

$form->field($model, 'starts_at')->widget(DateTimePicker::class, [
    'pluginOptions' => ['useCurrent' => false],
]);
```

`ColorPicker` keeps the HEX text field as the submitted value. Its suggested palette and native unrestricted color control stay hidden until the preview button is clicked. It supports both model-bound ActiveField usage and standalone `name`/`value` usage. Palette entries are limited to six-digit HEX colors; invalid entries are ignored. The icon class is normalized through the package safety policy.

The ColorPicker presentation and behavior live in `ColorPickerWidgetAsset` (`assets/css/colorpicker.css` and `assets/js/colorpicker.js`). Visual swatches and the compact preview are non-interactive canvas elements painted by the shared external initializer from validated HEX `data-color` values. The only `input[type=color]` is the actual unrestricted custom picker, so the markup avoids nested interactive controls and dynamic inline `style` attributes. Global outside-click/Escape handling is registered once for all ColorPicker instances on the page.

`DateTimePicker` uses the package Tempus Dominus stack, prepends the calendar icon, explicitly opens on icon click or input focus, and reuses a source/minified date-time AssetBundle already registered by the host layout when possible. `DatePicker` is the date-only variant. Both model-bound and standalone `name`/`value` rendering are supported.

Date/time instance configuration is serialized once as JSON in a `data-cinghie-datetime-options` attribute and then escaped by Yii as HTML. The shared external `DateTimePickerWidgetAsset` reads that data and initializes every picker; stable z-index styling also lives in the asset. This keeps multiple pickers on the same page from registering duplicate initializer blocks and follows the package CSP convention of avoiding package-owned inline script/style where practical.

`pluginOptions` must contain JSON-safe Tempus Dominus configuration values. Application-specific executable callbacks should remain in application-owned JavaScript rather than being passed through the PHP widget API.
