<?php

namespace App\Exceptions;


use Illuminate\Support\Facades\Response;
use Exception;


class JsonException extends Exception
{
    public function render($request )
    {
        return Response::error([$this->getMessage()] );
    }
}
