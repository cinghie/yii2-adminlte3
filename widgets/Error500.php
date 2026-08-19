<?php

namespace cinghie\adminlte3\widgets;

use cinghie\adminlte3\widgets\support\ErrorPage;

/**
 * Renders a safe AdminLTE-styled 500 page without exposing exception details.
 */
class Error500 extends ErrorPage
{
    public $title = 'Server error';
    public $message = 'An unexpected error occurred while processing your request.';

    protected function statusCode(): int
    {
        return 500;
    }

    protected function headlineClass(): string
    {
        return 'text-danger';
    }
}
