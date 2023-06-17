<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\PaymentMethods;

use App\Models\WorkSector\SystemConfigurationModels\PaymentMethod;
use App\Http\Requests\WorkSector\SystemConfigurations\UpdatingRequest;

class UpdatingPaymentMethodRequest extends UpdatingRequest
{
    protected function getModelName(): string
    {
        return PaymentMethod::class;
    }

    protected function getTableName(): string
    {
        return "payment_methods";
    }
    public function messages()
    {
        return [
            "name.unique" => "Payment Method's Name  Is Already Stored In Our Database !",
            "status.boolean" =>  "Payment Method's Status  Must Be Boolean",
        ];
    }
}
