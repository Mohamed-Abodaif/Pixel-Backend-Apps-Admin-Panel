<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\AssetsCategoriesOperations;

use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationUpdatingService;
use App\Http\Requests\WorkSector\SystemConfigurations\AssetsCategories\UpdatingAssetCategoryRequest;

class AssetsCategoryUpdatingService extends SystemConfigurationUpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Asset Category !";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The Asset Category Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingAssetCategoryRequest::class;
    }
}
