<?php

namespace App\Services\WorkSector\UsersModule;

use App\CustomLibs\ValidatorLib\JSONValidator;
use App\Http\Requests\Users\ProfileUpdatingRequest;
use Exception;
use Illuminate\Http\Request;

trait ValidationMethods
{

    /**
     * @param Request|array $request
     * @return ValidationMethods|UserUpdatingService
     * @throws Exception
     */
    protected function initValidator(Request | array $request): self
    {
        $this->validator = new JSONValidator($this->getRequestForm(), $request);
        $this->getRequestKeysToValidation();
        return $this;
    }

    abstract public function getRequestKeysToValidation(): array;
    protected function getRequestForm(): string
    {
        return ProfileUpdatingRequest::Class;
    }
}
