<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\TaxTypesOperations;

use App\Http\Requests\WorkSector\SystemConfigurations\TaxTypes\UpdatingTaxTypeRequest;
use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationUpdatingService;

class TaxTypeUpdatingService extends SystemConfigurationUpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Tax Type !";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The Tax Type Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingTaxTypeRequest::class;
    }
}