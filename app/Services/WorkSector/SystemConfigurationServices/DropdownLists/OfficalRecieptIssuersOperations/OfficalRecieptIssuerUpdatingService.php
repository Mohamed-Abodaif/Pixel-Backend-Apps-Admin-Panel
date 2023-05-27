<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownList\OfficalRecieptIssuersOperations;


use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationUpdatingService;
use App\Http\Requests\SystemConfigurationsRequests\OfficalRecieptIssuers\UpdatingOfficalRecieptIssuerRequest;

class OfficalRecieptIssuerUpdatingService extends SystemConfigurationUpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given OfficalRecieptIssuer !";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The OfficalRecieptIssuer Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingOfficalRecieptIssuerRequest::class;
    }
}
