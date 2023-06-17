<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\AssetsCategoriesOperations;

use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationDeletingService;


class AssetsCategoryDeletingService extends SystemConfigurationDeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given Asset Category";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The Asset Category Has Been Deleted Successfully !";
    }
}
