<?php

namespace App\Services\WorkSector;

use App\CustomLibs\ValidatorLib\ArrayValidator;
use App\CustomLibs\ValidatorLib\Validator;
use App\Exceptions\JsonException;
use App\Services\CoreServices\CRUDServices\StoringServices\StoringService;
use App\Services\CoreServices\CRUDServices\UpdatingServices\UpdatingService;
use Exception;
use Illuminate\Http\Request;

trait ValidationOperationsTrait
{
    protected Validator $validator;
    protected array $data = [];

    /**
     * @param Request $request
     * @return StoringService|UpdatingService
     * @throws Exception
     */
    protected function initValidator(Request $request): StoringService|UpdatingService
    {
        /** @var ArrayValidator $this->validator */
        $this->validator = new ArrayValidator($this->getRequestClass(), $request);
        return $this;
    }

    /**
     * @return StoringService|UpdatingService
     */
    protected function setRequestData(): StoringService|UpdatingService
    {
        $this->data = $this->validator->getRequestData();
        return $this;
    }

    /**
     * @return StoringService|UpdatingService
     * @throws JsonException
     */
    protected function validateData(): StoringService|UpdatingService
    {
        $validationResult = $this->validator->validate();
        if (is_array($validationResult)) { throw new JsonException(join(" , ", $validationResult)); }
        return $this;
    }

}
