<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\FeesOperations;

use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationDeletingService;



class FeesDeletingService extends SystemConfigurationDeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given Fees";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The Fees Has Been Deleted Successfully !";
    }
}
