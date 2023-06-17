<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\TaxTypes;

use App\Models\WorkSector\SystemConfigurationModels\TaxType;
use App\Http\Requests\WorkSector\SystemConfigurations\UpdatingRequest;



class UpdatingTaxTypeRequest extends UpdatingRequest
{
    protected function getModelName(): string
    {
        return TaxType::class;
    }

    protected function getTableName(): string
    {
        return "taxes_types";
    }
    public function messages()
    {
        return [
            "name.unique" => "Tax Type's Name  Is Already Stored In Our Database !",
            "status.boolean" =>  "Tax Type's Status  Must Be Boolean",
        ];
    }
}
