<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\ProductCategory;

use App\Http\Requests\WorkSector\SystemConfigurations\StoringRequest;


class StoringProductCategoryRequest extends StoringRequest
{
    protected function getTableName(): string
    {
        return "product_categories";
    }

    public function messages()
    {
        return array_merge(
            parent::messages(),
            [
                "items.*.name.required" => "ProductCategory's Name Has Not Been Sent !",
                "items.*.name.string" => "ProductCategory's Name Must Be String !",
                "items.*.name.max" => "ProductCategory's Name Must Not Be Greater THan 255 Character !",
                "items.*.status.boolean" => "ProductCategory's Status  Must Be Boolean",

                //single Validation Error Messages
                "name.unique" => "ProductCategory's Name  Is Already Stored In Our Database !"
            ]
        );
    }
}
