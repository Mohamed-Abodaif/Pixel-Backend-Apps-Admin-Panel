<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownList\TendersOperations;


use App\Http\Requests\WorkSector\SystemConfigurationsRequests\Tenders\UpdatingTenderRequest;
use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationUpdatingService;


class TenderUpdatingService extends SystemConfigurationUpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Tender !";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The Tender Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingTenderRequest::class;
    }
}
