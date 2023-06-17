<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\MeasurementUnits;

use App\Models\WorkSector\SystemConfigurationModels\MeasurementUnit;
use App\Http\Requests\WorkSector\SystemConfigurations\UpdatingRequest;

class UpdatingMeasurementUnitRequest extends UpdatingRequest
{
    protected function getModelName(): string
    {
        return MeasurementUnit::class;
    }

    protected function getTableName(): string
    {
        return "measurement_unites";
    }

    public function messages()
    {
        return [
            "name.unique" => "MeasurementUnit's Name Is Already Stored In Our Database !",
            "status.boolean" =>  "MeasurementUnit's Status  Must Be Boolean",
        ];
    }
}