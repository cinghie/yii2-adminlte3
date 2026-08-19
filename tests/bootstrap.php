<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/vendor/yiisoft/yii2/Yii.php';

if (Yii::$app === null) {
    new yii\web\Application([
        'id' => 'yii2-adminlte3-tests',
        'basePath' => dirname(__DIR__),
        'vendorPath' => dirname(__DIR__) . '/vendor',
        'components' => [
            'request' => [
                'cookieValidationKey' => 'test-key',
                'scriptFile' => __FILE__,
                'scriptUrl' => '/index.php',
            ],
        ],
    ]);
}
