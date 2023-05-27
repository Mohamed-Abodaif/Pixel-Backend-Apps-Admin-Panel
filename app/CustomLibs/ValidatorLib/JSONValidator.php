<?php

namespace App\CustomLibs\ValidatorLib;

use App\Helpers\Helpers;
use Illuminate\Contracts\Validation\Validator as ValidationResultOb;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Response;

class JSONValidator extends Validator
{

    protected function ErrorResponder(ValidationResultOb $validatorResultOb) : array | bool | JsonResponse | RedirectResponse
    {
        return Response::error( Helpers::getErrorsIndexedArray($validatorResultOb->errors()), 406 );
    }

}
