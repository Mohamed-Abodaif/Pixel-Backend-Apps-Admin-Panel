<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\CompanyBanckAccountsOperations;

use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationUpdatingService;
use App\Http\Requests\WorkSector\SystemConfigurations\CompanyBankAccounts\UpdatingCompanyBankAccountRequest;

class CompanyBankAccountUpdatingService extends SystemConfigurationUpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given CompanyBankAccount !";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The CompanyBankAccount Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingCompanyBankAccountRequest::class;
    }
}
