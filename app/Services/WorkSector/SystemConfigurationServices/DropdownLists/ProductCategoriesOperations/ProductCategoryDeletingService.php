<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\ProductCategoriesOperations;

use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationDeletingService;



class ProductCategoryDeletingService extends SystemConfigurationDeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given Product Category!";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The ProductCategory Has Been Deleted Successfully!";
    }
}
