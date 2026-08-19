<?php

namespace cinghie\adminlte3;

use yii\web\AssetBundle;

/**
 * Optional AdminLTE iCheck Bootstrap styles.
 */
class AdminLTEIcheckAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/';
    public $appendTimestamp = true;

    public $css = [
        'plugins/icheck-bootstrap/icheck-bootstrap.css',
    ];
}
