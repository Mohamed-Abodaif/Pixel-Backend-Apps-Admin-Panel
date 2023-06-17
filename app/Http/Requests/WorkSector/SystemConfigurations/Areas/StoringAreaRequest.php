<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\Areas;

use App\Http\Requests\WorkSector\SystemConfigurations\StoringRequest;


class StoringAreaRequest extends StoringRequest
{
    protected function getTableName(): string
    {
        return "departments";
    }

    public function messages()
    {
        return array_merge(
            parent::messages(),
            [
                "items.*.name.required" => "Area's Name Has Not Been Sent !",
                "items.*.name.string" => "Area's Name Must Be String !",
                "items.*.name.max" => "Area's Name Must Not Be Greater THan 255 Character !",
                "items.*.status.boolean" => "Area's Status  Must Be Boolean",

                //single Validation Error Messages
                "name.unique" => "Area's Name  Is Already Stored In Our Database !"
            ]
        );
    }
}
