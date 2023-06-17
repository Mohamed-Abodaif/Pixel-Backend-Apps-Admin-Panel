<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\TaxTypesOperations;

use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationDeletingService;


class TaxTypeDeletingService extends SystemConfigurationDeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given Tax Type";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The Tax Type Has Been Deleted Successfully !";
    }
}
