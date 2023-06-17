<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\Branches;

use App\Models\WorkSector\SystemConfigurationModels\Branch;
use App\Http\Requests\WorkSector\SystemConfigurations\UpdatingRequest;

class UpdatingBranchRequest extends UpdatingRequest
{
    protected function getModelName(): string
    {
        return Branch::class;
    }

    protected function getTableName(): string
    {
        return "branches";
    }

    public function messages()
    {
        return [
            "name.unique" => "Branch's Name Is Already Stored In Our Database !",
            "status.boolean" =>  "Branch's Status  Must Be Boolean",
        ];
    }
}