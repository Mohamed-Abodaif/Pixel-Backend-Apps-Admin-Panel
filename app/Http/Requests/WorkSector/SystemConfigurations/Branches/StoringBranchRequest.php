<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\Branches;

use App\Http\Requests\WorkSector\SystemConfigurations\StoringRequest;


class StoringBranchRequest extends StoringRequest
{
    protected function getTableName(): string
    {
        return "branches";
    }

    public function messages()
    {
        return array_merge(
            parent::messages(),
            [
                "items.*.name.required" => "Branch's Name Has Not Been Sent !",
                "items.*.name.string" => "Branch's Name Must Be String !",
                "items.*.name.max" => "Branch's Name Must Not Be Greater THan 255 Character !",
                "items.*.status.boolean" => "Branch's Status  Must Be Boolean",

                //single Validation Error Messages
                "name.unique" => "Branch's Name  Is Already Stored In Our Database !"
            ]
        );
    }
}
