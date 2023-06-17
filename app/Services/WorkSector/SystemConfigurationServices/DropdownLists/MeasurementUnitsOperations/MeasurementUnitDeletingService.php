<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\MeasurementUnitsOperations;

use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationDeletingService;



class MeasurementUnitDeletingService extends SystemConfigurationDeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given MeasurementUnit";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The MeasurementUnit Has Been Deleted Successfully !";
    }
}
