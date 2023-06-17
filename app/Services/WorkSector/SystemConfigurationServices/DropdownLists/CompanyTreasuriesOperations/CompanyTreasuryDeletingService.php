<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\CompanyTreasuriesOperations;

use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationDeletingService;



class CompanyTreasuryDeletingService extends SystemConfigurationDeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given CompanyTreasury";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The CompanyTreasury Has Been Deleted Successfully !";
    }
}
