<?php

namespace cinghie\adminlte3;

use yii\web\AssetBundle;

/**
 * Optional minified AdminLTE iCheck Bootstrap styles.
 */
class AdminLTEIcheckMinifyAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/';
    public $appendTimestamp = true;

    public $css = [
        'plugins/icheck-bootstrap/icheck-bootstrap.min.css',
    ];
}
