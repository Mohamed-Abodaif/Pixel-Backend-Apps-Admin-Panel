<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\MeasurementUnits;

use App\Http\Requests\WorkSector\SystemConfigurations\StoringRequest;


class StoringMeasurementUnitRequest extends StoringRequest
{
    protected function getTableName(): string
    {
        return "measurement_unites";
    }

    public function messages()
    {
        return array_merge(
            parent::messages(),
            [
                "items.*.name.required" => "MeasurementUnit's Name Has Not Been Sent !",
                "items.*.name.string" => "MeasurementUnit's Name Must Be String !",
                "items.*.name.max" => "MeasurementUnit's Name Must Not Be Greater THan 255 Character !",
                "items.*.status.boolean" => "MeasurementUnit's Status  Must Be Boolean",

                //single Validation Error Messages
                "name.unique" => "MeasurementUnit's Name  Is Already Stored In Our Database !"
            ]
        );
    }
}
