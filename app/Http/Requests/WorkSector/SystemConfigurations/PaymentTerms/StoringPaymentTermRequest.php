<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\PaymentTerms;

use App\Http\Requests\WorkSector\SystemConfigurations\StoringRequest;


class StoringPaymentTermRequest extends StoringRequest
{
    protected function getTableName(): string
    {
        return "payment_terms";
    }

    public function messages()
    {
        return array_merge(
            parent::messages(),
            [
                "items.*.name.required" => "Payment Term's Name Has Not Been Sent !",
                "items.*.name.string" => "Payment Term's Name Must Be String !",
                "items.*.name.max" => "Payment Term's Name Must Not Be Greater THan 255 Character !",
                "items.*.status.boolean" => "Payment Term's Status  Must Be Boolean",

                //single Validation Error Messages
                "name.unique" => "Payment Term's Name  Is Already Stored In Our Database !"
            ]
        );
    }
}
