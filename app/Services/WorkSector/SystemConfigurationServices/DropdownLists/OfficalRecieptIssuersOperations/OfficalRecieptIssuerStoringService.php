<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\OfficalRecieptIssuersOperations;

use Exception;
use App\CustomLibs\ValidatorLib\Validator;
use App\Http\Requests\WorkSector\SystemConfigurations\OfficalRecieptIssuer\StoringOfficalRecieptIssuerRequest;
use App\Services\WorkSector\Interfaces\NeedToStoreDateFields;
use App\Services\WorkSector\Interfaces\MustCreatedMultiplexed;
use App\Models\WorkSector\SystemConfigurationModels\OfficalRecieptIssuer;
use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationStoringService;

class OfficalRecieptIssuerStoringService extends SystemConfigurationStoringService implements MustCreatedMultiplexed, NeedToStoreDateFields
{

    protected function getDefinitionCreatingFailingErrorMessage(): string
    {
        return "Failed To Create The Given OfficalRecieptIssuer !";
    }

    protected function getDefinitionCreatingSuccessMessage(): string
    {
        return "The OfficalRecieptIssuer Has Been Created Successfully !";
    }

    protected function getDefinitionModelClass(): string
    {
        return OfficalRecieptIssuer::class;
    }

    protected function getRequestClass(): string
    {
        return StoringOfficalRecieptIssuerRequest::class;
    }

    //There Is No Key Will Be Used If IsItMultipleCreation returns false
    //Because Model itself will determine the fillable values from request main data array
    public function getRequestDataKey(): string
    {
        return "items";
    }

    protected function getFillableColumns(): array
    {
        return ["name", "status"];
    }

    public function getDateFieldNames(): array
    {
        return ["created_at", "updated_at"];
    }
    public function setRequestGeneralValidationRules(): Validator
    {
        return $this->validator->ExceptRules(["name"]);
    }

    /**
     * @return Validator
     * @throws Exception
     */
    public function setSingleRowValidationRules(): Validator
    {
        return $this->validator->OnlyRules(["name"]);
    }
}