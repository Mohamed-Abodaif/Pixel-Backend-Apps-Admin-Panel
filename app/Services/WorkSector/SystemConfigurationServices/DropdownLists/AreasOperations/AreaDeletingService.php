<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\AreasOperations;

use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationDeletingService;



class AreaDeletingService extends SystemConfigurationDeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given Area";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The Area Has Been Deleted Successfully !";
    }
}
