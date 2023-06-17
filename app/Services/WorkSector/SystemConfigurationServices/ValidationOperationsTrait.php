<?php

namespace App\Services\WorkSector\SystemConfigurationServices;

use Exception;
use Illuminate\Http\Request;
use App\CustomLibs\ValidatorLib\Validator;
use App\CustomLibs\ValidatorLib\ArrayValidator;
use App\Services\WorkSector\Interfaces\MustCreatedMultiplexed;

trait ValidationOperationsTrait
{
    protected Validator $validator;
    protected array $data = [];

    /**
     * @param Request $request
     * @return SystemConfigurationStoringService|SystemConfigurationUpdatingService|ValidationOperationsTrait
     * @throws Exception
     */
    protected function initValidator(Request $request): self
    {
        /** @var ArrayValidator $this->validator */
        $this->validator = new ArrayValidator($this->getRequestClass(), $request);

        if ($this instanceof MustCreatedMultiplexed) {
            $this->setRequestGeneralValidationRules();
        }
        return $this;
    }

    protected function getRequestData(): self
    {
        $this->data = $this->validator->getRequestData();
        return $this;
    }

    /**
     * @return SystemConfigurationStoringService|SystemConfigurationUpdatingService|ValidationOperationsTrait
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

    protected function getFillablesValues(array $data, array $dateFields = []): array
    {

        $fillableValues = [];
        foreach ($data as $index => $row) {
            $fillables = $this->processFillableValues($row);
            //If No Fillable Filed Is Set ... There Is No Need To This Row
            if (empty($fillables)) {
                continue;
            }
            $fillableValues[$index] =  $fillables;

            if (empty($dateFields)) {
                continue;
            }
            $fillableValues[$index] = $this->setDateFieldsValues($fillableValues[$index],  $dateFields);
        }
        return $fillableValues;
    }

    protected function processFillableValues(array $sourceDataRow): array
    {
        $fillableValues = [];
        foreach ($this->getFillableColumns() as $column) {
            if (isset($sourceDataRow[$column])) {
                $fillableValues[$column] = $sourceDataRow[$column];
            }
        }
        return $fillableValues;
    }

    protected function setDateFieldsValues(array $dataArrayToChange, array $dateFields = []): array
    {
        foreach ($dateFields as $field) {
            $dataArrayToChange[$field] = now();
        }
        return $dataArrayToChange;
    }
}
