<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\ServiceCategoriesOperations;

use App\Http\Requests\WorkSector\SystemConfigurations\ServiceCategory\UpdatingServiceCategoryRequest;
use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationUpdatingService;

class ServiceCategoryUpdatingService extends SystemConfigurationUpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Service Category!";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The ServiceCategory Has Been Updated Successfully!";
    }

    protected function getRequestClass(): string
    {
        return UpdatingServiceCategoryRequest::class;
    }
}
