<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownList\TimesheetSubCategoriesOperations;



use App\Services\SystemConfigurationsManagementServices\SystemConfigurationDeletingService;

class TimesheetSubCategoryDeletingService extends SystemConfigurationDeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given Timesheet Sub Category ";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The Timesheet Sub Category Has Been Deleted Successfully !";
    }
}
