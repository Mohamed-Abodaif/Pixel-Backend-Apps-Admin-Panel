<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\Fees;

use App\Models\WorkSector\SystemConfigurationModels\Fees;
use App\Http\Requests\WorkSector\SystemConfigurations\UpdatingRequest;

class UpdatingFeesRequest extends UpdatingRequest
{
    protected function getModelName(): string
    {
        return Fees::class;
    }

    protected function getTableName(): string
    {
        return "fees";
    }

    public function messages()
    {
        return [
            "name.unique" => "Fee's Name Is Already Stored In Our Database !",
            "status.boolean" =>  "Fee's Status  Must Be Boolean",
        ];
    }
}