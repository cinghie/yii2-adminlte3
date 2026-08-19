<?php

namespace cinghie\adminlte3;

use yii\web\AssetBundle;

/**
 * Backward-compatible aggregate minified AdminLTE 3 asset bundle.
 *
 * This convenience bundle preserves the historical plugin set. New pages that
 * do not need jQuery UI, Tempus Dominus or iCheck should prefer
 * {@see AdminLTECoreMinifyAsset} and register optional plugin bundles explicitly.
 */
class AdminLTEMinifyAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/';
    public $appendTimestamp = true;

    public $depends = [
        AdminLTEJqueryUiMinifyAsset::class,
        AdminLTEDateTimeMinifyAsset::class,
        AdminLTEIcheckMinifyAsset::class,
        AdminLTECoreMinifyAsset::class,
    ];
}
