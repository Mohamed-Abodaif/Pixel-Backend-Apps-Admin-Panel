<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\BranchesOperations;

use App\Http\Requests\WorkSector\SystemConfigurations\Branches\UpdatingBranchRequest;
use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationUpdatingService;


class BranchUpdatingService extends SystemConfigurationUpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Branch !";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The Branch Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingBranchRequest::class;
    }
}
