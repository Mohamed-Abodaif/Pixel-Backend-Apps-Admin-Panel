<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\PaymentTerms;

use App\Models\WorkSector\SystemConfigurationModels\PaymentTerm;
use App\Http\Requests\WorkSector\SystemConfigurations\UpdatingRequest;

class UpdatingPaymentTermRequest extends UpdatingRequest
{
    protected function getModelName(): string
    {
        return PaymentTerm::class;
    }

    protected function getTableName(): string
    {
        return "payment_terms";
    }
    public function messages()
    {
        return [
            "name.unique" => "Payment Term's Name  Is Already Stored In Our Database !",
            "status.boolean" =>  "Payment Term's Status  Must Be Boolean",
        ];
    }
}
