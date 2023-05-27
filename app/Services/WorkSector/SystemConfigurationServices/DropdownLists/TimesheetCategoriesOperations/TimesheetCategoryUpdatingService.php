<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownList\TimesheetCategoriesOperations;



use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationUpdatingService;
use App\Http\Requests\WorkSector\SystemConfigurationsRequests\TimesheetCategories\UpdatingTimesheetCategoryRequest;

class TimesheetCategoryUpdatingService extends SystemConfigurationUpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Timesheet Category !";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The Timesheet Category Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingTimesheetCategoryRequest::class;
    }
}
