<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\BranchesOperations;

use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationDeletingService;



class BranchDeletingService extends SystemConfigurationDeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given Branch";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The Branch Has Been Deleted Successfully !";
    }
}
