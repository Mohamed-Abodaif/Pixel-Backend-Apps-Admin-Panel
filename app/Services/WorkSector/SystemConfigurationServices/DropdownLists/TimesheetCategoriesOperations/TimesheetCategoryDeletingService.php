<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\TimesheetCategoriesOperations;

use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationDeletingService;

class TimesheetCategoryDeletingService extends SystemConfigurationDeletingService
{

    protected function getDefinitionDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given Timesheet Category ";
    }

    protected function getDefinitionDeletingSuccessMessage(): string
    {
        return "The Timesheet Category Has Been Deleted Successfully !";
    }
}
