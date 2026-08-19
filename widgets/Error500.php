<?php

namespace cinghie\adminlte3\widgets;

use cinghie\adminlte3\widgets\support\ErrorPage;

/**
 * Renders a safe AdminLTE-styled 500 page without exposing exception details.
 */
class Error500 extends ErrorPage
{
    public $title = 'Oops! Something went wrong.';
    public $message = 'We will work on fixing that right away.';

    protected function statusCode(): int
    {
        return 500;
    }

    protected function headlineClass(): string
    {
        return 'text-danger';
    }
}
