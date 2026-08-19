<?php

namespace cinghie\adminlte3\assets;

use yii\web\AssetBundle;

/**
 * Publishes package-owned AdminLTE theme overrides and widget behavior.
 */
class AdminLTEThemeAsset extends AssetBundle
{
    /** @var string Source directory published by Yii's asset manager. */
    public $sourcePath = __DIR__;

    /** @var bool Append source timestamps for browser cache invalidation. */
    public $appendTimestamp = true;

    /** @var string[] Package CSS files. */
    public $css = [
        'css/adminlte-theme.css',
        'css/widgets.css',
        'css/progress-widths.css',
    ];

    /** @var string[] Package JavaScript files. */
    public $js = [
        'js/widgets.js',
    ];

    /**
     * @var array{only?: array<string>, except?: array<string>, caseSensitive?: bool, beforeCopy?: callable(): mixed, afterCopy?: callable(): mixed, forceCopy?: bool}
     */
    public $publishOptions = [
        'only' => [
            'css/*',
            'js/*',
        ],
    ];

    /** @var array<class-string> No additional Yii AssetBundle dependencies. */
    public $depends = [];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if (defined('YII_DEBUG') && YII_DEBUG) {
            $this->publishOptions['forceCopy'] = true;
        }
    }
}
