<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\ExpenseTypes;


use Illuminate\Validation\Rule;
use App\Models\WorkSector\SystemConfigurationModels\ExpenseType;
use App\Http\Requests\WorkSector\SystemConfigurations\UpdatingRequest;

class UpdatingExpenseTypeRequest extends UpdatingRequest
{
    protected function getModelName(): string
    {
        return ExpenseType::class;
    }

    protected function getTableName(): string
    {
        return "expense_types";
    }

    public function messages()
    {
        return [
            "name.unique" => "Expense Type's Name Is Already Stored In Our Database !",
            "status.boolean" =>  "Expense Type's Status  Must Be Boolean",
            "category.in" =>  "Expense Type's Category Value Is Invalid !",
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
                'category' => ["bail", "nullable", 'string', Rule::in(ExpenseType::CATEGORY_TYPES), 'max:225'],
            ]
        );
    }
}
