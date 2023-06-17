<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\DepartmentsOperations;

use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationDeletingService;



class DepartmentDeletingService extends SystemConfigurationDeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given Department";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The Department Has Been Deleted Successfully !";
    }
}
