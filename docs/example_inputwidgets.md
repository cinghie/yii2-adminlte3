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

`ColorPicker` keeps the HEX text field as the submitted value. Its suggested palette and native unrestricted color control stay hidden until the preview button is clicked. It supports both model-bound ActiveField usage and standalone `name`/`value` usage.

`DateTimePicker` uses the package Tempus Dominus stack, prepends the calendar icon, explicitly opens on icon click or input focus, and reuses a source/minified date-time AssetBundle already registered by the host layout when possible. `DatePicker` is the date-only variant.
