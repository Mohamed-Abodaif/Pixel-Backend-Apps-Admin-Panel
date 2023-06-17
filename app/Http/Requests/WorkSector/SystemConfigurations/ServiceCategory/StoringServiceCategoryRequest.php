<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\ServiceCategory;

use App\Http\Requests\WorkSector\SystemConfigurations\StoringRequest;


class StoringServiceCategoryRequest extends StoringRequest
{
    protected function getTableName(): string
    {
        return "service_categories";
    }

    public function messages()
    {
        return array_merge(
            parent::messages(),
            [
                "items.*.name.required" => "ServiceCategory's Name Has Not Been Sent !",
                "items.*.name.string" => "ServiceCategory's Name Must Be String !",
                "items.*.name.max" => "ServiceCategory's Name Must Not Be Greater THan 255 Character !",
                "items.*.status.boolean" => "ServiceCategory's Status  Must Be Boolean",

                //single Validation Error Messages
                "name.unique" => "ServiceCategory's Name  Is Already Stored In Our Database !"
            ]
        );
    }
}
