<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\CompanyBanckAccountsOperations;

use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationDeletingService;



class CompanyBankAccountDeletingService extends SystemConfigurationDeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given CompanyBankAccount";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The CompanyBankAccount Has Been Deleted Successfully !";
    }
}
