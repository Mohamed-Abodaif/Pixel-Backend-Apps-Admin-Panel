<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\PaymentMethods;

use App\Http\Requests\WorkSector\SystemConfigurations\StoringRequest;


class StoringPaymentMethodRequest extends StoringRequest
{
    protected function getTableName(): string
    {
        return "payment_methods";
    }

    public function messages()
    {
        return array_merge(
            parent::messages(),
            [
                "items.*.name.required" => "Payment Method's Name Has Not Been Sent !",
                "items.*.name.string" => "Payment Method's Name Must Be String !",
                "items.*.name.max" => "Payment Method's Name Must Not Be Greater THan 255 Character !",
                "items.*.status.boolean" => "Payment Method's Status  Must Be Boolean",

                //single Validation Error Messages
                "name.unique" => "Payment Method's Name  Is Already Stored In Our Database !"
            ]
        );
    }
}
