<?php

namespace cinghie\adminlte3\widgets;

use Yii;
use yii\bootstrap4\Alert as BootstrapAlert;
use yii\bootstrap4\Widget;
use yii\helpers\Html;

class Alert extends Widget
{
    public $alertTypes = [
        'error' => ['class' => 'alert-danger', 'icon' => 'fas fa-ban'],
        'danger' => ['class' => 'alert-danger', 'icon' => 'fas fa-ban'],
        'success' => ['class' => 'alert-success', 'icon' => 'fas fa-check'],
        'info' => ['class' => 'alert-info', 'icon' => 'fas fa-info'],
        'warning' => ['class' => 'alert-warning', 'icon' => 'fas fa-exclamation-triangle'],
    ];

    public $closeButton = [
        'tag' => 'button',
        'type' => 'button',
        'class' => 'close',
        'data-dismiss' => 'alert',
        'aria-label' => 'Close',
    ];
    public $encodeMessages = true;
    public $removeFlashAfterDisplay = true;
    public $options = [];

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
            $icon = isset($config['icon']) ? Html::tag('i', '', ['class' => 'icon ' . $config['icon']]) : '';
            foreach ((array) $data as $message) {
                $options = $baseOptions;
                Html::addCssClass($options, $config['class']);
                $options['id'] = $this->getId() . '-' . $type . '-' . $index++;
                $options['role'] = 'alert';
                $body = $icon . ($this->encodeMessages ? Html::encode($message) : $message);

                echo BootstrapAlert::widget([
                    'body' => $body,
                    'closeButton' => $this->closeButton,
                    'options' => $options,
                ]);
            }

            if ($this->removeFlashAfterDisplay && !Yii::$app->request->isAjax) {
                $session->removeFlash($type);
            }
        }
    }
}
