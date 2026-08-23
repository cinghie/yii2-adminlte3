<?php

namespace cinghie\adminlte3\assets;

use yii\web\AssetBundle;

/**
 * Package-owned presentation and initializer for ColorPicker.
 */
class ColorPickerWidgetAsset extends AssetBundle
{
    public $sourcePath = '@cinghie/adminlte3/assets';

    public $css = [
        'css/colorpicker.css',
    ];

    public $js = [
        'js/colorpicker.js',
    ];

    public $appendTimestamp = true;
}
