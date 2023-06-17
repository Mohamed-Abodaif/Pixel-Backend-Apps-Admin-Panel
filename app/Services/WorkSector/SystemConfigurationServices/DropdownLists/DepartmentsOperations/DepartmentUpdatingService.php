<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\DepartmentsOperations;

use App\Http\Requests\WorkSector\SystemConfigurations\Departments\UpdatingDepartmentRequest;
use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationUpdatingService;


class DepartmentUpdatingService extends SystemConfigurationUpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Department !";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The Department Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingDepartmentRequest::class;
    }
}
