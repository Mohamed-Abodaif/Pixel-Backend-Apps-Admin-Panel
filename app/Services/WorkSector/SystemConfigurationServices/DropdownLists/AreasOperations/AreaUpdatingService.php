<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\AreasOperations;

use App\Http\Requests\WorkSector\SystemConfigurations\Areas\UpdatingAreaRequest;
use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationUpdatingService;

class AreaUpdatingService extends SystemConfigurationUpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Area !";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The Area Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingAreaRequest::class;
    }
}
