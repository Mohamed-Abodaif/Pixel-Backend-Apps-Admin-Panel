<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownList\ComapnyBankAccountsOperations;

use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationDeletingService;



class ComapnyBankAccountDeletingService extends SystemConfigurationDeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given ComapnyBankAccount";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The ComapnyBankAccount Has Been Deleted Successfully !";
    }
}
