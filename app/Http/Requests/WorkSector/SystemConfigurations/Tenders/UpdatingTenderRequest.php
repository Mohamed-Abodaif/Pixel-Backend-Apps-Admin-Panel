<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\Tenders;

use App\Models\WorkSector\SystemConfigurationModels\Tender;
use App\Http\Requests\WorkSector\SystemConfigurations\UpdatingRequest;


class UpdatingTenderRequest extends UpdatingRequest
{
    protected function getModelName(): string
    {
        return Tender::class;
    }

    protected function getTableName(): string
    {
        return "tenders";
    }

    public function messages()
    {
        return [
            "name.unique" => "Tender's Name  Is Already Stored In Our Database !",
            "status.boolean" =>  "Tender's Status  Must Be Boolean",
        ];
    }
}
