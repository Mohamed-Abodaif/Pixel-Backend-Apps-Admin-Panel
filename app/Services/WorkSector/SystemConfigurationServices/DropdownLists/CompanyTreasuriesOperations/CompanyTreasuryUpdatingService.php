<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\CompanyTreasuriesOperations;

use App\Http\Requests\WorkSector\SystemConfigurations\CompanyTreasury\UpdatingCompanyTreasuryRequest;
use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationUpdatingService;

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
