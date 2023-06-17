<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\ServiceCategoriesOperations;

use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationDeletingService;



class ServiceCategoryDeletingService extends SystemConfigurationDeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given Service Category!";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The ServiceCategory Has Been Deleted Successfully!";
    }
}
