<?php

namespace cinghie\adminlte3\assets;

use yii\web\AssetBundle;

/**
 * Package-owned presentation and initializer for ColorPicker.
 */
class ColorPickerWidgetAsset extends AssetBundle
{
    public $sourcePath = __DIR__;

    public $css = [
        'css/colorpicker.css',
    ];

    public $js = [
        'js/colorpicker.js',
    ];

    public $appendTimestamp = true;

    public $publishOptions = [
        'only' => [
            'css/colorpicker.css',
            'js/colorpicker.js',
        ],
    ];
}
