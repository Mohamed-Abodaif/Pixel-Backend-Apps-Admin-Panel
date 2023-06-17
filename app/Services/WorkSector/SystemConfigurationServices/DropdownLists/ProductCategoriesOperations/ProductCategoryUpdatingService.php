<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\ProductCategoriesOperations;

use App\Http\Requests\WorkSector\SystemConfigurations\ProductCategory\UpdatingProductCategoryRequest;
use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationUpdatingService;


class ProductCategoryUpdatingService extends SystemConfigurationUpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Product Category!";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The ProductCategory Has Been Updated Successfully!";
    }

    protected function getRequestClass(): string
    {
        return UpdatingProductCategoryRequest::class;
    }
}
