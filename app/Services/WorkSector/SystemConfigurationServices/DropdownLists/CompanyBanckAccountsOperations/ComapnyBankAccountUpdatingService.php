<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownList\ComapnyBankAccountsOperations;


use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationUpdatingService;
use App\Http\Requests\SystemConfigurationsRequests\ComapnyBankAccounts\UpdatingComapnyBankAccountRequest;

class ComapnyBankAccountUpdatingService extends SystemConfigurationUpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given ComapnyBankAccount !";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The ComapnyBankAccount Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingComapnyBankAccountRequest::class;
    }
}
