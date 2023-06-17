<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\Departments;

use App\Http\Requests\WorkSector\SystemConfigurations\StoringRequest;


class StoringDepartmentRequest extends StoringRequest
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
                "items.*.name.required" => "Department's Name Has Not Been Sent !",
                "items.*.name.string" => "Department's Name Must Be String !",
                "items.*.name.max" => "Department's Name Must Not Be Greater THan 255 Character !",
                "items.*.status.boolean" => "Department's Status  Must Be Boolean",

                //single Validation Error Messages
                "name.unique" => "Department's Name  Is Already Stored In Our Database !"
            ]
        );
    }
}
