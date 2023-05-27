<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownList\AreasOperations;


use App\Http\Requests\SystemConfigurationsRequests\Areas\UpdatingAreaRequest;
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
