<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownList\CompanyTreasurysOperations;


use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationUpdatingService;
use App\Http\Requests\SystemConfigurationsRequests\CompanyTreasurys\UpdatingCompanyTreasuryRequest;

class CompanyTreasuryUpdatingService extends SystemConfigurationUpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given CompanyTreasury !";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The CompanyTreasury Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingCompanyTreasuryRequest::class;
    }
}
