<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\ProductCategory;

use App\Http\Requests\WorkSector\SystemConfigurations\UpdatingRequest;
use App\Models\WorkSector\SystemConfigurationModels\ProductCategory;

class UpdatingProductCategoryRequest extends UpdatingRequest
{
    protected function getModelName(): string
    {
        return ProductCategory::class;
    }

    protected function getTableName(): string
    {
        return "product_categories";
    }

    public function messages()
    {
        return [
            "name.unique" => "Product Category's Name Is Already Stored In Our Database !",
            "status.boolean" =>  "Product Category's Status  Must Be Boolean",
        ];
    }
}