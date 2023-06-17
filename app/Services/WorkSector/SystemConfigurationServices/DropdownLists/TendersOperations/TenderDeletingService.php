<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\TendersOperations;

use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationDeletingService;



class TenderDeletingService extends SystemConfigurationDeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given Tender";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The Tender Has Been Deleted Successfully !";
    }
}
