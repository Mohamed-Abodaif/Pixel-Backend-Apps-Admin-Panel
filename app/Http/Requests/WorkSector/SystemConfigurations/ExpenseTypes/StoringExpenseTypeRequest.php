<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\ExpenseTypes;

use Illuminate\Validation\Rule;
use App\Models\WorkSector\SystemConfigurationModels\ExpenseType;
use App\Http\Requests\WorkSector\SystemConfigurations\StoringRequest;

class StoringExpenseTypeRequest extends StoringRequest
{
    protected function getTableName(): string
    {
        return "expense_types";
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
                'items.*.category' => ["bail", "required", 'string',  Rule::in(ExpenseType::CATEGORY_TYPES), 'max:225'],
            ]
        );
    }
    public function messages()
    {
        return array_merge(
            parent::messages(),
            [
                "items.*.name.required" => "Expense Type's Name Has Not Been Sent !",
                "items.*.name.string" => "Expense Type's Name Must Be String !",
                "items.*.name.max" => "Expense Type's Name Must Not Be Greater THan 255 Character !",
                "items.*.status.boolean" => "Expense Type's Status  Must Be Boolean",

                "items.*.category.required" => "Expense Type's Category Has Not Been Sent !",
                "items.*.category.in" => "Expense Type's Category Value Is Invalid !",

                //single Validation Error Messages
                "name.unique" => "Expense Type's Name  Is Already Stored In Our Database !"
            ]
        );
    }
}