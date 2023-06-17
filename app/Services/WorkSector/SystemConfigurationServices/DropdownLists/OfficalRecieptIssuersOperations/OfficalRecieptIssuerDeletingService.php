<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\OfficalRecieptIssuersOperations;

use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationDeletingService;

class OfficalRecieptIssuerDeletingService extends SystemConfigurationDeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given OfficalRecieptIssuer";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The OfficalRecieptIssuer Has Been Deleted Successfully !";
    }
}
