<?php

namespace App\CustomLibs\ValidatorLib;

use App\Helpers\Helpers;
use Illuminate\Contracts\Validation\Validator as ValidationResultOb;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Response;

class ArrayValidator extends Validator
{
    protected function ErrorResponder(ValidationResultOb $validatorResultOb) : array | bool | JsonResponse | RedirectResponse
    {
        return  Helpers::getErrorsIndexedArray($validatorResultOb->errors());
    }

}
