<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\Fees;

use App\Http\Requests\WorkSector\SystemConfigurations\StoringRequest;


class StoringFeeRequest extends StoringRequest
{
    protected function getTableName(): string
    {
        return "fees";
    }

    public function rules()
    {
        return [
            "items" => ["bail", "required", "array"],
            "items.*.name" => ["bail", "required", "string", "max:255"],
            "items.*.percentage" => ["bail", "required", "float"],
            "items.*.status" =>   ["bail", "nullable", "boolean"],
            "name" => ["unique:" . $this->getTableName()],
        ];
    }
    
    public function messages()
    {
        return array_merge(
            parent::messages(),
            [
                "items.*.name.required" => "Fee's Name Has Not Been Sent !",
                "items.*.name.string" => "Fee's Name Must Be String !",
                "items.*.name.max" => "Fee's Name Must Not Be Greater THan 255 Character !",
                "items.*.percentage.required" => "Fee's Percentage Has Not Been Sent !",
                "items.*.percentage.float" => "Fee's Percentage Must Be Float !",
                "items.*.percentage.max" => "Fee's Percentage Must Not Be Greater THan 255 Character !",
                "items.*.status.boolean" => "Fee's Status  Must Be Boolean",

                //single Validation Error Messages
                "name.unique" => "Fee's Name  Is Already Stored In Our Database !"
            ]
        );
    }
}
