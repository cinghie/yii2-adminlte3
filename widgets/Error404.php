<?php

namespace cinghie\adminlte3\widgets;

use cinghie\adminlte3\widgets\support\ErrorPage;

/**
 * Renders a safe AdminLTE-styled 404 page without exposing exception details.
 */
class Error404 extends ErrorPage
{
    public $title = 'Page not found';
    public $message = 'The page you requested could not be found.';

    protected function statusCode(): int
    {
        return 404;
    }

    protected function headlineClass(): string
    {
        return 'text-warning';
    }
}
