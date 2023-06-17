<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\CompanyBanckAccountsOperations;

use Exception;

use App\CustomLibs\ValidatorLib\Validator;
use App\Services\WorkSector\Interfaces\NeedToStoreDateFields;
use App\Services\WorkSector\Interfaces\MustCreatedMultiplexed;
use App\Models\WorkSector\SystemConfigurationModels\CompanyBankAccount;
use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationStoringService;
use App\Http\Requests\WorkSector\SystemConfigurations\CompanyBankAccounts\StoringCompanyBankAccountRequest;

class CompanyBankAccountStoringService extends SystemConfigurationStoringService implements MustCreatedMultiplexed, NeedToStoreDateFields
{

    protected function getDefinitionCreatingFailingErrorMessage(): string
    {
        return "Failed To Create The Given Company Bank Account !";
    }

    protected function getDefinitionCreatingSuccessMessage(): string
    {
        return "The Company Bank Account Has Been Created Successfully !";
    }

    protected function getDefinitionModelClass(): string
    {
        return CompanyBankAccount::class;
    }

    protected function getRequestClass(): string
    {
        return StoringCompanyBankAccountRequest::class;
    }

    //There Is No Key Will Be Used If IsItMultipleCreation returns false
    //Because Model itself will determine the fillable values from request main data array
    public function getRequestDataKey(): string
    {
        return "items";
    }

    protected function getFillableColumns(): array
    {
        return ["account_name", "bank_name", "bank_branch", "country_id", "city_id", "currency_id", "account_type", "swift_code", "account_number", "iban_number", "status"];
    }

    public function getDateFieldNames(): array
    {
        return ["created_at", "updated_at"];
    }
    public function setRequestGeneralValidationRules(): Validator
    {
        return $this->validator->AllRules($this->getFillableColumns());
    }

    /**
     * @return Validator
     * @throws Exception
     */
    public function setSingleRowValidationRules(): Validator
    {
        return $this->validator;
    }
}
