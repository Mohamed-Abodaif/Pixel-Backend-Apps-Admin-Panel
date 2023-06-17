<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\TaxTypes;

use App\Http\Requests\WorkSector\SystemConfigurations\StoringRequest;


class StoringTaxTypeRequest extends StoringRequest
{
    protected function getTableName(): string
    {
        return "taxes_types";
    }

    public function messages()
    {
        return array_merge(
            parent::messages(),
            [
                "items.*.name.required" => "Tax Type's Name Has Not Been Sent !",
                "items.*.name.string" => "Tax Type's Name Must Be String !",
                "items.*.name.max" => "Tax Type's Name Must Not Be Greater THan 255 Character !",
                "items.*.status.boolean" => "Tax Type's Status  Must Be Boolean",

                //single Validation Error Messages
                "name.unique" => "Tax Type's Name  Is Already Stored In Our Database !"
            ]
        );
    }
}
