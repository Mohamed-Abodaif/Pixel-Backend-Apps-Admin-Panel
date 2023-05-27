<?php

namespace App\Services\WorkSector\UsersModule\ResetPasswordService;

use App\CustomLibs\ValidatorLib\JSONValidator;
use App\CustomLibs\ValidatorLib\Validator;
use Illuminate\Http\Request;
use Exception;

abstract class BaseService
{
    protected Validator $validator;

    abstract protected function getRequestForm(): string;

    /**
     * @param Request|array $request
     * @return $this
     * @throws Exception
     */
    protected function initValidator(Request | array $request): self
    {
        $this->validator = new JSONValidator($this->getRequestForm(), $request);
        return $this;
    }
}
