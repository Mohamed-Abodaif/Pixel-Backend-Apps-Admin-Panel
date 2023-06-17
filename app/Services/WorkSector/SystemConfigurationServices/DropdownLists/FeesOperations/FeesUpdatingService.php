<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\FeesOperations;

use App\Http\Requests\WorkSector\SystemConfigurations\Fees\UpdatingFeesRequest;
use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationUpdatingService;

class FeesUpdatingService extends SystemConfigurationUpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Fees !";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The Fees Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingFeesRequest::class;
    }
}
