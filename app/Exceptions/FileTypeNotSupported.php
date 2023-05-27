<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Response;

class FileTypeNotSupported extends Exception
{

    public function report()
    {
        return false;
    }

    public function render($request, Exception $exception)
    {
        return Response::error($exception->getMessage(), 415);
    }
}
