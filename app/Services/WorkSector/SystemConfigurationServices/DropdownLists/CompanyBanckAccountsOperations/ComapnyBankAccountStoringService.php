<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownList\ComapnyBankAccountsOperations;

use Exception;

use App\CustomLibs\ValidatorLib\Validator;
use App\Models\WorkSector\SystemConfigurationModels\ComapnyBankAccount;
use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationStoringService;
use App\Services\WorkSector\SystemConfigurationsManagementServices\Interfaces\NeedToStoreDateFields;
use App\Services\WorkSector\SystemConfigurationsManagementServices\Interfaces\MustCreatedMultiplexed;

class ComapnyBankAccountStoringService extends SystemConfigurationStoringService implements MustCreatedMultiplexed, NeedToStoreDateFields
{

    protected function getDefinitionCreatingFailingErrorMessage(): string
    {
        return "Failed To Create The Given ComapnyBankAccount !";
    }

    protected function getDefinitionCreatingSuccessMessage(): string
    {
        return "The ComapnyBankAccount Has Been Created Successfully !";
    }

    protected function getDefinitionModelClass(): string
    {
        return ComapnyBankAccount::class;
    }

    protected function getRequestClass(): string
    {
        return StoringComapnyBankAccountRequest::class;
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
