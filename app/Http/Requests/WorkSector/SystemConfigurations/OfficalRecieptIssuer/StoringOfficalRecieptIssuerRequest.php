<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\OfficalRecieptIssuer;

use App\Http\Requests\WorkSector\SystemConfigurations\StoringRequest;


class StoringOfficalRecieptIssuerRequest extends StoringRequest
{
    protected function getTableName(): string
    {
        return "offical_reciept_issuers";
    }

    public function messages()
    {
        return array_merge(
            parent::messages(),
            [
                "items.*.name.required" => "Offical Reciept Name Has Not Been Sent !",
                "items.*.name.string" => "Offical Reciept Name Must Be String !",
                "items.*.name.max" => "Offical Reciept Must Not Be Greater THan 255 Character !",
                "items.*.status.boolean" => "Offical Reciept Status  Must Be Boolean",

                //single Validation Error Messages
                "name.unique" => "Offical Reciept Name  Is Already Stored In Our Database !"
            ]
        );
    }
}
