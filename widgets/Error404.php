<?php

namespace cinghie\adminlte3\widgets;

use cinghie\adminlte3\widgets\support\ErrorPage;

/**
 * Renders a safe AdminLTE-styled 404 page without exposing exception details.
 */
class Error404 extends ErrorPage
{
    public $title = 'Oops! Page not found.';
    public $message = 'We could not find the page you were looking for.';

    protected function statusCode(): int
    {
        return 404;
    }

    protected function headlineClass(): string
    {
        return 'text-warning';
    }
}
