<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\CustodySendersOperations;

use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationUpdatingService;
use App\Http\Requests\WorkSector\SystemConfigurations\CustodySenders\UpdatingCustodySenderRequest;

class CustodySenderUpdatingService extends SystemConfigurationUpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Custody Sender !";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The Custody Sender Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingCustodySenderRequest::class;
    }
}