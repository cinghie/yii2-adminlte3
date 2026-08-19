<?php

namespace cinghie\adminlte3\assets;

use yii\web\AssetBundle;

/**
 * Package theme / layout overrides for AdminLTE 3.
 */
class AdminLTEThemeAsset extends AssetBundle
{
    public $sourcePath = __DIR__;
    public $appendTimestamp = true;
    public $css = [
        'css/adminlte-theme.css',
        'css/widgets.css',
    ];
    public $publishOptions = [
        'only' => [
            'css/*',
        ],
    ];
    public $depends = [];

    public function init()
    {
        parent::init();
        if (defined('YII_DEBUG') && YII_DEBUG) {
            $this->publishOptions['forceCopy'] = true;
        }
    }
}
