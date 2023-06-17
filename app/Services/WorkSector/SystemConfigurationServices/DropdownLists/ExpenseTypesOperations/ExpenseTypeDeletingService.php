<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\ExpenseTypesOperations;

use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationDeletingService;



class ExpenseTypeDeletingService extends SystemConfigurationDeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given Expense Type";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The Expense Type Has Been Deleted Successfully !";
    }
}
