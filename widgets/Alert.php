<?php

namespace cinghie\adminlte3\widgets;

use Yii;
use yii\bootstrap4\Alert as BootstrapAlert;
use yii\bootstrap4\Widget;
use yii\helpers\Html;

/**
 * Renders supported Yii flash messages as Bootstrap 4 / AdminLTE alerts.
 *
 * Flash messages are HTML-encoded by default. Set {@see $encodeMessages} to
 * false only when the complete flash-message source is trusted HTML.
 */
class Alert extends Widget
{
    /** @var array<string,array{class:string,icon:string}> Flash type presentation map. */
    public $alertTypes = [
        'error' => ['class' => 'alert-danger', 'icon' => 'fas fa-ban'],
        'danger' => ['class' => 'alert-danger', 'icon' => 'fas fa-ban'],
        'success' => ['class' => 'alert-success', 'icon' => 'fas fa-check'],
        'info' => ['class' => 'alert-info', 'icon' => 'fas fa-info'],
        'warning' => ['class' => 'alert-warning', 'icon' => 'fas fa-exclamation-triangle'],
    ];

    /** @var array|false Close-button configuration passed to Bootstrap Alert. */
    public $closeButton = [
        'tag' => 'button',
        'type' => 'button',
        'class' => 'close',
        'data-dismiss' => 'alert',
        'aria-label' => 'Close',
    ];

    /** @var bool Whether to HTML-encode flash-message content. */
    public $encodeMessages = true;

    /** @var bool Whether displayed flashes are removed after a non-AJAX render. */
    public $removeFlashAfterDisplay = true;

    /** @var array Base HTML options merged into every rendered alert. */
    public $options = [];

    /**
     * {@inheritdoc}
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
            $icon = isset($config['icon'])
                ? Html::tag('i', '', ['class' => 'icon ' . $config['icon']])
                : '';

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
