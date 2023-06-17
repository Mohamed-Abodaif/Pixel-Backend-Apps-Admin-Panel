<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\Areas;

use App\Models\WorkSector\SystemConfigurationModels\Area;
use App\Http\Requests\WorkSector\SystemConfigurations\UpdatingRequest;

class UpdatingAreaRequest extends UpdatingRequest
{
    protected function getModelName(): string
    {
        return Area::class;
    }

    protected function getTableName(): string
    {
        return "departments";
    }

    public function messages()
    {
        return [
            "name.unique" => "Area's Name Is Already Stored In Our Database !",
            "status.boolean" =>  "Area's Status  Must Be Boolean",
        ];
    }
}