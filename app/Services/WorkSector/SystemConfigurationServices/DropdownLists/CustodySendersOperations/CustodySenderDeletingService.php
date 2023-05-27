<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownList\CustodySendersOperations;

use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationDeletingService;


class CustodySenderDeletingService extends SystemConfigurationDeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given Custody Sender";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The Custody Sender Has Been Deleted Successfully !";
    }
}
