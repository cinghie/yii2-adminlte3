<?php

/**
 * @copyright Copyright &copy; Gogodigital Srls
 * @company Gogodigital Srls - Wide ICT Solutions
 * @website http://www.gogodigital.it
 * @github https://github.com/cinghie/yii2-adminlte3
 * @license GNU GENERAL PUBLIC LICENSE VERSION 3
 * @package yii2-AdminLTE
 * @version 0.1.0
 */

namespace cinghie\adminlte3\widgets;

use Yii;
use yii\bootstrap4\Alert as BootstrapAlert;
use yii\bootstrap4\Widget;
use yii\helpers\Html;

/**
 * Alert widget for AdminLTE 3 with Bootstrap 4.
 *
 * Renders flash messages as AdminLTE 3 alerts (with icon and optional close button).
 * Supports: error, danger, success, info, warning.
 *
 * @see https://adminlte.io/docs/3.1/components/alerts.html
 */
class Alert extends Widget
{
    /**
     * @var array alert type configuration. Key = flash key, value = ['class' => 'alert-*', 'icon' => 'fas fa-*']
     */
    public $alertTypes = [
        'error' => [
            'class' => 'alert-danger',
            'icon' => 'fas fa-ban',
        ],
        'danger' => [
            'class' => 'alert-danger',
            'icon' => 'fas fa-ban',
        ],
        'success' => [
            'class' => 'alert-success',
            'icon' => 'fas fa-check',
        ],
        'info' => [
            'class' => 'alert-info',
            'icon' => 'fas fa-info',
        ],
        'warning' => [
            'class' => 'alert-warning',
            'icon' => 'fas fa-exclamation-triangle',
        ],
    ];

    /**
     * @var array|false options for the close button. False to hide close button.
     * Default includes AdminLTE 3 / Bootstrap 4 compatible markup.
     */
    public $closeButton = [
        'tag' => 'button',
        'type' => 'button',
        'class' => 'close',
        'data-dismiss' => 'alert',
        'aria-label' => 'Close',
    ];

    /**
     * @var bool whether to HTML-encode flash messages (set false if messages contain HTML)
     */
    public $encodeMessages = true;

    /**
     * @var bool whether to remove flash messages after displaying (when not AJAX)
     */
    public $removeFlashAfterDisplay = true;

    /**
     * @var array default HTML options for the alert container
     */
    public $options = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $session = Yii::$app->getSession();
        $flashes = $session->getAllFlashes();
        $baseOptions = $this->options;
        $index = 0;

        foreach ($flashes as $type => $data) {
            if (!isset($this->alertTypes[$type])) {
                continue;
            }

            $config = $this->alertTypes[$type];
            $data = (array) $data;
            $icon = isset($config['icon']) ? Html::tag('i', '', ['class' => 'icon ' . $config['icon']]) : '';

            foreach ($data as $message) {
                $options = array_merge([], $baseOptions);
                Html::addCssClass($options, $config['class']);
                $options['id'] = $this->getId() . '-' . $type . '-' . $index;
                $options['role'] = 'alert';

                $body = $icon . ($this->encodeMessages ? Html::encode($message) : $message);

                echo BootstrapAlert::widget([
                    'body' => $body,
                    'closeButton' => $this->closeButton,
                    'options' => $options,
                ]);

                $index++;
            }

            if ($this->removeFlashAfterDisplay && !Yii::$app->request->isAjax) {
                $session->removeFlash($type);
            }
        }
    }
}
