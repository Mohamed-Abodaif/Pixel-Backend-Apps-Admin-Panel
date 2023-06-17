<?php

namespace App\Services\CoreServices\CRUDServices\Traits;

use App\CustomLibs\ValidatorLib\ArrayValidator;
use App\CustomLibs\ValidatorLib\Validator;
use App\Services\CoreServices\CRUDServices\DataWriterCRUDService;
use Exception;
use Illuminate\Http\Request;

trait ValidationTrait
{
    protected Validator $validator;
    protected array $data = [];

    /**
     * @param Request $request
     * @return DataWriterCRUDService|ValidationTrait
     * @throws Exception
     */
    protected function initValidator(Request $request): self
    {
        /** @var ArrayValidator $this->validator */
        $this->validator = new ArrayValidator($this->getRequestClass(), $request);
        return $this;
    }

    protected function setRequestData(): self
    {
        $this->data = $this->validator->getRequestData();
        return $this;
    }

    /**
     * @return DataWriterCRUDService|ValidationTrait
     * @throws Exception
     */
    protected function validateData(): self
    {
        $validationResult = $this->validator->validate();
        if (is_array($validationResult)) {
            throw new Exception(join(" , ", $validationResult));
        }
        return $this;
    }

}
