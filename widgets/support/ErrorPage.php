<?php

namespace cinghie\adminlte3\widgets\support;

use yii\bootstrap4\Widget;

/**
 * Internal base renderer for dedicated public HTTP error widgets.
 *
 * @internal
 */
abstract class ErrorPage extends Widget
{
    /** @var string User-facing error title. */
    public $title;

    /** @var string User-facing error message. */
    public $message;

    /** @var string|array Home/dashboard URL accepted by Yii's URL helper. */
    public $homeUrl = ['/'];

    /** @var string Accessible label for the navigation action. */
    public $homeLabel = 'Return to dashboard';

    /** @var array HTML options for the outer error-page element. */
    public $options = [];

    /**
     * @return int HTTP status code shown by the widget.
     */
    abstract protected function statusCode(): int;

    /**
     * @return string AdminLTE contextual text class.
     */
    abstract protected function headlineClass(): string;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->render('@cinghie/adminlte3/views/error/page', [
            'statusCode' => $this->statusCode(),
            'headlineClass' => $this->headlineClass(),
            'title' => (string) $this->title,
            'message' => (string) $this->message,
            'homeUrl' => $this->homeUrl,
            'homeLabel' => (string) $this->homeLabel,
            'options' => $this->options,
        ]);
    }
}
