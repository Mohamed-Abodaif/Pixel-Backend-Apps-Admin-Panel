<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\MeasurementUnitsOperations;


use App\Http\Requests\WorkSector\SystemConfigurations\MeasurementUnits\UpdatingMeasurementUnitRequest;
use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationUpdatingService;

class MeasurementUnitUpdatingService extends SystemConfigurationUpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given MeasurementUnit !";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The MeasurementUnit Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingMeasurementUnitRequest::class;
    }
}
