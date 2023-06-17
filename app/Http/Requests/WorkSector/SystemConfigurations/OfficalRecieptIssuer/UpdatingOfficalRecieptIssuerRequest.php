<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\OfficalRecieptIssuer;

use App\Http\Requests\WorkSector\SystemConfigurations\UpdatingRequest;
use App\Models\WorkSector\SystemConfigurationModels\OfficalRecieptIssuer;

class UpdatingOfficalRecieptIssuerRequest extends UpdatingRequest
{
    protected function getModelName(): string
    {
        return OfficalRecieptIssuer::class;
    }

    protected function getTableName(): string
    {
        return "offical_reciept_issuers";
    }
    public function messages()
    {
        return [
            "name.unique" => "Offical Reciept's Name  Is Already Stored In Our Database !",
            "status.boolean" =>  "Offical Reciept's Status  Must Be Boolean",
        ];
    }
}
