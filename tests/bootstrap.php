<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/vendor/yiisoft/yii2/Yii.php';

$basePath = dirname(__DIR__);
$runtimePath = $basePath . '/runtime';
if (!is_dir($runtimePath)) {
    mkdir($runtimePath, 0777, true);
}

if (Yii::$app === null) {
    new yii\web\Application([
        'id' => 'yii2-adminlte3-tests',
        'basePath' => $basePath,
        'runtimePath' => $runtimePath,
        'vendorPath' => $basePath . '/vendor',
        'params' => [
            'bsVersion' => '4.x',
        ],
        'modules' => [
            'gridview' => [
                'class' => kartik\grid\Module::class,
            ],
        ],
        'components' => [
            'request' => [
                'cookieValidationKey' => 'test-key',
                'scriptFile' => __FILE__,
                'scriptUrl' => '/index.php',
            ],
            'i18n' => [
                'translations' => [
                    'traits' => [
                        'class' => yii\i18n\PhpMessageSource::class,
                        'basePath' => __DIR__ . '/messages',
                    ],
                    'crm' => [
                        'class' => yii\i18n\PhpMessageSource::class,
                        'basePath' => __DIR__ . '/messages',
                    ],
                ],
            ],
        ],
    ]);
}
