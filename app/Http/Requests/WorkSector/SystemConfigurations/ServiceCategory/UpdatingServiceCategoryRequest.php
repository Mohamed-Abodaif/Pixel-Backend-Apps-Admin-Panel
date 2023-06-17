<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\ServiceCategory;

use App\Models\WorkSector\SystemConfigurationModels\ServiceCategory;
use App\Http\Requests\WorkSector\SystemConfigurations\UpdatingRequest;

class UpdatingServiceCategoryRequest extends UpdatingRequest
{
    protected function getModelName(): string
    {
        return ServiceCategory::class;
    }

    protected function getTableName(): string
    {
        return "service_categories";
    }

    public function messages()
    {
        return [
            "name.unique" => "ServiceCategory's Name Is Already Stored In Our Database !",
            "status.boolean" =>  "ServiceCategory's Status  Must Be Boolean",
        ];
    }
}