<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\TimesheetCategories;

use App\Http\Requests\WorkSector\SystemConfigurations\StoringRequest;


class StoringTimesheetCategoryRequest extends StoringRequest
{
    protected function getTableName(): string
    {
        return "employees_timesheet_categories";
    }

    public function messages()
    {
        return array_merge(
            parent::messages(),
            [
                "items.*.name.required" => "Timesheet Category's Name Has Not Been Sent !",
                "items.*.name.string" => "Timesheet Category's Name Must Be String !",
                "items.*.name.max" => "Timesheet Category's Name Must Not Be Greater THan 255 Character !",
                "items.*.status.boolean" => "Timesheet Category's Status  Must Be Boolean",

                //single Validation Error Messages
                "name.unique" => "Timesheet Category's Name  Is Already Stored In Our Database !"
            ]
        );
    }
}
