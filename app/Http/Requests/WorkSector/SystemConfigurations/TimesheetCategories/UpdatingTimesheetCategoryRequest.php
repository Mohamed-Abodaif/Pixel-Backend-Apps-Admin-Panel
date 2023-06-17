<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\TimesheetCategories;

use App\Models\WorkSector\SystemConfigurationModels\TimeSheetCategory;
use App\Http\Requests\WorkSector\SystemConfigurations\UpdatingRequest;


class UpdatingTimesheetCategoryRequest extends UpdatingRequest
{
    protected function getModelName(): string
    {
        return TimeSheetCategory::class;
    }

    protected function getTableName(): string
    {
        return "employees_timesheet_categories";
    }

    public function messages()
    {
        return [
            "name.unique" => "Timesheet Category's Name  Is Already Stored In Our Database !",
            "status.boolean" =>  "Timesheet Category's Status  Must Be Boolean",
        ];
    }
}
