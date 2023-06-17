<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\TimesheetSubCategories;

use App\Http\Requests\WorkSector\SystemConfigurations\StoringRequest;



class StoringTimesheetSubCategoryRequest extends StoringRequest
{
    protected function getTableName(): string
    {
        return "employees_timesheet_sub_categories";
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                'items.*.options' => ["bail", "required", "array", "min:1"],
                'items.*.timesheet_category_id' => ["bail", "required", "integer"],
                'items.*.name' => ["required", "string"],
                'items.*.status' => ["boolean"]


            ]
        );
    }

    public function messages()
    {
        return array_merge(
            parent::messages(),
            [
                "items.*.name.required" => "Timesheet Sub Category's Name Has Not Been Sent !",
                "items.*.name.string" => "Timesheet Sub Category's Name Must Be String !",
                "items.*.name.max" => "Timesheet Sub Category's Name Must Not Be Greater THan 255 Character !",
                "items.*.status.boolean" => "Timesheet Sub Category's Status  Must Be Boolean",

                "items.*.options.required" => "Timesheet Sub Category's Options Has Not Been Sent !",
                "items.*.options.array" => "Timesheet Sub Category's Options Value Must Be An Array",
                "items.*.options.min" => "Timesheet Sub Category's Options Must Contain One Option At Least !",
                "items.*.timesheet_category_id.required" => "Timesheet Sub Category's Parent Category ID Has Not Been Sent !",
                "items.*.timesheet_category_id.integer" => "Timesheet Sub Category's Parent Category ID Must Be An Integer",

                //single Validation Error Messages
                "name.unique" => "Timesheet Sub Category's Name  Is Already Stored In Our Database !",
                "timesheet_category_id.exists" => "Timesheet Sub Category's Parent Category Is Not Found In Our Database !"
            ]
        );
    }
}
