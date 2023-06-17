<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\TimesheetCategoriesOperations;

use Exception;
use App\CustomLibs\ValidatorLib\Validator;
use App\Http\Requests\WorkSector\SystemConfigurations\TimesheetCategories\StoringTimesheetCategoryRequest;
use App\Services\WorkSector\Interfaces\NeedToStoreDateFields;
use App\Services\WorkSector\Interfaces\MustCreatedMultiplexed;
use App\Models\WorkSector\SystemConfigurationModels\TimeSheetCategory;
use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationStoringService;

class TimesheetCategoryStoringService extends SystemConfigurationStoringService implements MustCreatedMultiplexed, NeedToStoreDateFields
{

    protected function getDefinitionCreatingFailingErrorMessage(): string
    {
        return "Failed To Create The Given Timesheet Category !";
    }

    protected function getDefinitionCreatingSuccessMessage(): string
    {
        return "The Timesheet Category Has Been Created Successfully !";
    }

    protected function getDefinitionModelClass(): string
    {
        return TimeSheetCategory::class;
    }

    protected function getRequestClass(): string
    {
        return StoringTimesheetCategoryRequest::class;
    }

    //There Is No Key Will Be Used If IsItMultipleCreation returns false
    //Because Model itself will determine the fillable values from request main data array
    public function getRequestDataKey(): string
    {
        return "items";
    }

    protected function getFillableColumns(): array
    {
        return ["name", "status"];
    }
    public function getDateFieldNames(): array
    {
        return ["created_at", "updated_at"];
    }

    public function setRequestGeneralValidationRules(): Validator
    {
        return $this->validator->ExceptRules(["name"]);
    }

    /**
     * @return Validator
     * @throws Exception
     */
    public function setSingleRowValidationRules(): Validator
    {
        return $this->validator->OnlyRules(["name"]);
    }
}
