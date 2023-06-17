<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\AssetsCategoriesOperations;

use Exception;
use App\CustomLibs\ValidatorLib\Validator;
use App\Http\Requests\WorkSector\SystemConfigurations\AssetsCategories\StoringAssetCategoryRequest;
use App\Models\WorkSector\SystemConfigurationModels\AssetsCategory;
use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationStoringService;
use App\Services\WorkSector\Interfaces\NeedToStoreDateFields;
use App\Services\WorkSector\Interfaces\MustCreatedMultiplexed;

class AssetsCategoryStoringService extends SystemConfigurationStoringService implements MustCreatedMultiplexed, NeedToStoreDateFields
{

    protected function getDefinitionCreatingFailingErrorMessage(): string
    {
        return "Failed To Create The Given Asset Category !";
    }

    protected function getDefinitionCreatingSuccessMessage(): string
    {
        return "The New Asset Category Has Been Created Successfully !";
    }

    protected function getDefinitionModelClass(): string
    {
        return AssetsCategory::class;
    }

    protected function getRequestClass(): string
    {
        return StoringAssetCategoryRequest::class;
    }

    //From MustCreatedMultiplexed Interface
    public function getRequestDataKey(): string
    {
        return "items";
    }
    protected function getFillableColumns(): array
    {
        return ["name", "status"];
    }

    //From NeedToStoreDateFields Interface
    public function getDateFieldNames(): array
    {
        return ["created_at", "updated_at"];
    }

    /**
     * @return Validator
     */
    public function setRequestGeneralValidationRules(): Validator
    {
        return $this->validator->ExceptRules(["name"]);
    }

    /**
     * @return Validator
     * @throws Exception
     */
    public function setSingleRowValidationRules(): Validator
    {
        return $this->validator->OnlyRules(["name"]);
    }
}
