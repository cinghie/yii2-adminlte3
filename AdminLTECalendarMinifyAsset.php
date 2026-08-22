<?php

namespace cinghie\adminlte3;

use Yii;
use yii\bootstrap4\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Optional minified FullCalendar assets bundled with AdminLTE 3.
 */
class AdminLTECalendarMinifyAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/';
    public $appendTimestamp = true;

    public $css = [
        'plugins/fullcalendar/main.min.css',
    ];

    public $js = [
        'plugins/fullcalendar/main.min.js',
    ];

    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        $root = rtrim(Yii::getAlias($this->sourcePath), '/\\') . DIRECTORY_SEPARATOR;
        if (is_file($root . 'plugins/fullcalendar/locales-all.min.js')) {
            $this->js[] = 'plugins/fullcalendar/locales-all.min.js';
        }

        if (!is_file($root . 'plugins/fullcalendar-daygrid/main.min.css')) {
            return;
        }

        // AdminLTE 3.0.x ships FullCalendar 4 plugin families separately.
        // AdminLTE 3.1+ bundles them into plugins/fullcalendar/main.*.
        $this->css = [
            'plugins/fullcalendar/main.min.css',
            'plugins/fullcalendar-daygrid/main.min.css',
            'plugins/fullcalendar-timegrid/main.min.css',
            'plugins/fullcalendar-bootstrap/main.min.css',
        ];
        $this->js = [
            'plugins/fullcalendar/main.min.js',
            'plugins/fullcalendar-interaction/main.min.js',
            'plugins/fullcalendar-daygrid/main.min.js',
            'plugins/fullcalendar-timegrid/main.min.js',
            'plugins/fullcalendar-bootstrap/main.min.js',
        ];
        if (is_file($root . 'plugins/fullcalendar/locales-all.min.js')) {
            $this->js[] = 'plugins/fullcalendar/locales-all.min.js';
        }
    }
}
