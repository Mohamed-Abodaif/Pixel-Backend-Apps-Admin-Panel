<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\TimesheetSubCategories;

use App\Models\WorkSector\SystemConfigurationModels\TimeSheetSubCategory;
use App\Http\Requests\WorkSector\SystemConfigurations\UpdatingRequest;


class UpdatingTimesheetSubCategoryRequest extends UpdatingRequest
{
    protected function getModelName(): string
    {
        return TimeSheetSubCategory::class;
    }

    protected function getTableName(): string
    {
        return "employees_timesheet_sub_categories";
    }
    public function messages()
    {
        return [
            "name.unique" => "Timesheet Category's Name  Is Already Stored In Our Database !",
            "status.boolean" =>  "Timesheet Category's Status  Must Be Boolean",

            "options.array" => "Timesheet Sub Category's Options Value Must Be An Array",
            "options.min" => "Timesheet Sub Category's Options Must Contain One Option At Least !",
            "timesheet_category_id.integer" => "Timesheet Sub Category's Parent Category ID Must Be An Integer",

        ];
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules($data)
    {
        return array_merge(
            parent::rules($data),
            [
                'options' => ["bail", "nullable", "array", "min:1"],
                'timesheet_category_id' => ["bail", "nullable", "exists:employees_timesheet_categories,id"]
            ]
        );
    }
}
