<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\AssetsCategories;

use App\Models\WorkSector\SystemConfigurationModels\AssetsCategory;
use App\Http\Requests\WorkSector\SystemConfigurations\UpdatingRequest;


class UpdatingAssetCategoryRequest extends UpdatingRequest
{

    protected function getTableName(): string
    {
        return "assets_categories";
    }

    protected function getModelName(): string
    {
        return AssetsCategory::class;
    }

    public function messages()
    {
        return [
            "name.unique" => "Asset Category's Name Is Already Stored In Our Database !",
            "status.boolean" =>  "Asset Category's Status  Must Be Boolean",
        ];
    }
}
