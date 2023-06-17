<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\AssetsCategories;

use App\Http\Requests\WorkSector\SystemConfigurations\StoringRequest;


class StoringAssetCategoryRequest extends StoringRequest
{
    protected function getTableName(): string
    {
        return "assets_categories";
    }

    public function messages()
    {
        return array_merge(
            parent::messages(),
            [
                "items.*.name.required" => "Asset Category's Name Has Not Been Sent !",
                "items.*.name.string" => "Asset Category's Name Must Be String !",
                "items.*.name.max" => "Asset Category's Name Must Not Be Greater THan 255 Character !",
                "items.*.status.boolean" => "Asset Category's Status  Must Be Boolean",

                //single Validation Error Messages
                "name.unique" => "Asset Category's Name  Is Already Stored In Our Database !"
            ]
        );
    }
}
