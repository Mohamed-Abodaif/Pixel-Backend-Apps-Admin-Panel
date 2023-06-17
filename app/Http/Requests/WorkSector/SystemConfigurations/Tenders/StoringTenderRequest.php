<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\Tenders;

use App\Http\Requests\WorkSector\SystemConfigurations\StoringRequest;



class StoringTenderRequest extends StoringRequest
{
    protected function getTableName(): string
    {
        return "tenders";
    }

    public function messages()
    {
        return array_merge(
            parent::messages(),
            [
                "items.*.name.required" => "Tender's Name Has Not Been Sent !",
                "items.*.name.string" => "Tender's Name Must Be String !",
                "items.*.name.max" => "Tender's Name Must Not Be Greater THan 255 Character !",
                "items.*.status.boolean" => "Tender's Status  Must Be Boolean",

                //single Validation Error Messages
                "name.unique" => "Tender's Name  Is Already Stored In Our Database !"
            ]
        );
    }
}
