<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\TimesheetSubCategoriesOperations;


use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationUpdatingService;
use App\Http\Requests\WorkSector\SystemConfigurations\TimesheetSubCategories\UpdatingTimesheetSubCategoryRequest;

class TimesheetSubCategoryUpdatingService extends SystemConfigurationUpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Timesheet Sub Category !";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The Timesheet Sub Category Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdatingTimesheetSubCategoryRequest::class;
    }
}
