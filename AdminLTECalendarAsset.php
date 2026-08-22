<?php

namespace cinghie\adminlte3;

use Yii;
use yii\bootstrap4\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Optional FullCalendar assets bundled with AdminLTE 3.
 */
class AdminLTECalendarAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/';
    public $appendTimestamp = true;

    public $css = [
        'plugins/fullcalendar/main.css',
    ];

    public $js = [
        'plugins/fullcalendar/main.js',
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
        if (is_file($root . 'plugins/fullcalendar/locales-all.js')) {
            $this->js[] = 'plugins/fullcalendar/locales-all.js';
        }

        if (!is_file($root . 'plugins/fullcalendar-daygrid/main.css')) {
            return;
        }

        // AdminLTE 3.0.x ships FullCalendar 4 plugin families separately.
        // AdminLTE 3.1+ bundles them into plugins/fullcalendar/main.*.
        $this->css = [
            'plugins/fullcalendar/main.css',
            'plugins/fullcalendar-daygrid/main.css',
            'plugins/fullcalendar-timegrid/main.css',
            'plugins/fullcalendar-bootstrap/main.css',
        ];
        $this->js = [
            'plugins/fullcalendar/main.js',
            'plugins/fullcalendar-interaction/main.js',
            'plugins/fullcalendar-daygrid/main.js',
            'plugins/fullcalendar-timegrid/main.js',
            'plugins/fullcalendar-bootstrap/main.js',
        ];
        if (is_file($root . 'plugins/fullcalendar/locales-all.js')) {
            $this->js[] = 'plugins/fullcalendar/locales-all.js';
        }
    }
}
