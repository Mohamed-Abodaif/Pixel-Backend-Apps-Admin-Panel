<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\ExpenseTypesOperations;

use Exception;
use App\CustomLibs\ValidatorLib\Validator;

use App\Http\Requests\WorkSector\SystemConfigurations\ExpenseTypes\StoringExpenseTypeRequest;
use App\Models\WorkSector\SystemConfigurationModels\ExpenseType;
use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationStoringService;
use App\Services\WorkSector\Interfaces\NeedToStoreDateFields;
use App\Services\WorkSector\Interfaces\MustCreatedMultiplexed;

class ExpenseTypeStoringService extends SystemConfigurationStoringService implements MustCreatedMultiplexed, NeedToStoreDateFields
{

    protected function getDefinitionCreatingFailingErrorMessage(): string
    {
        return "Failed To Create The Given Expense Type !";
    }

    protected function getDefinitionCreatingSuccessMessage(): string
    {
        return "The New Expense Type Has Been Created Successfully !";
    }

    protected function getDefinitionModelClass(): string
    {
        return ExpenseType::class;
    }

    protected function getRequestClass(): string
    {
        return StoringExpenseTypeRequest::class;
    }

    //There Is No Key Will Be Used If IsItMultipleCreation returns false
    //Because Model itself will determine the fillable values from request main data array
    public function getRequestDataKey(): string
    {
        return "items";
    }

    //If The Updating Operation Will Be Executed On One Data Row ... There IS No Need To define any fields
    protected function getFillableColumns(): array
    {
        return ["name", "status", "category"];
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
