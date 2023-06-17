<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\companyTreasury;

use App\Http\Requests\WorkSector\SystemConfigurations\StoringRequest;


class StoringComapnyTreasuryRequest extends StoringRequest
{
    protected function getTableName(): string
    {
        return "company_treasuries";
    }

    public function messages()
    {
        return array_merge(
            parent::messages(),
            [
                "items.*.name.required" => "companyTreasury's Name Has Not Been Sent !",
                "items.*.name.string" => "companyTreasury's Name Must Be String !",
                "items.*.name.max" => "companyTreasury's Name Must Not Be Greater THan 255 Character !",
                "items.*.status.boolean" => "companyTreasury's Status  Must Be Boolean",

                //single Validation Error Messages
                "name.unique" => "companyTreasury's Name  Is Already Stored In Our Database !"
            ]
        );
    }
}
