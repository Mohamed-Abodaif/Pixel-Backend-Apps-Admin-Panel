<?php

namespace App\Services\WorkSector\SystemConfigurationsManagementServices\Interfaces;

use App\CustomLibs\ValidatorLib\Validator;

interface MustCreatedMultiplexed
{
    //To get Request Data Array Key ... EX : "items" key will contain the data those required to store
    public function getRequestDataKey(): string;

    /**
     * @return Validator
     */
    public function setRequestGeneralValidationRules(): Validator;

    /**
     * @return Validator
     */
    public function setSingleRowValidationRules(): Validator;
}
