<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownList\TimesheetSubCategoriesOperations;

use Exception;
use App\CustomLibs\ValidatorLib\Validator;
use App\Models\WorkSector\SystemConfigurationModels\TimeSheetSubCategory;
use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationStoringService;
use App\Services\WorkSector\SystemConfigurationsManagementServices\Interfaces\NeedToStoreDateFields;
use App\Http\Requests\WorkSector\SystemConfigurationsRequests\TimesheetSubCategories\StoringTimesheetSubCategoryRequest;

class TimesheetSubCategoryStoringService extends SystemConfigurationStoringService implements NeedToStoreDateFields
{

    protected function getDefinitionCreatingFailingErrorMessage(): string
    {
        return "Failed To Create The Given Timesheet Sub Category !";
    }

    protected function getDefinitionCreatingSuccessMessage(): string
    {
        return "The Timesheet Sub Category Has Been Created Successfully !";
    }

    protected function getDefinitionModelClass(): string
    {
        return TimeSheetSubCategory::class;
    }

    protected function getRequestClass(): string
    {
        return StoringTimesheetSubCategoryRequest::class;
    }

    public function getRequestDataKey(): string
    {
        return "items";
    }

    protected function getFillableColumns(): array
    {
        return ['name', 'timesheet_category_id', 'options', "status"];
    }
    public function getDateFieldNames(): array
    {
        return ["created_at", "updated_at"];
    }
    public function setRequestGeneralValidationRules(): Validator
    {
        return $this->validator->ExceptRules(["name", "timesheet_category_id"]);
    }

    /**
     * @return Validator
     * @throws Exception
     */
    public function setSingleRowValidationRules(): Validator
    {
        return $this->validator->OnlyRules(["name", "timesheet_category_id"]);
    }

    private function changeMultiCreationOptionsDataType(): void
    {
        $dataKey = $this->getRequestDataKey();
        $data = $this->data[$dataKey];
        for ($i = 0; $i < count($data); $i++) {
            $options = $data[$i]["options"];
            if (is_array($options)) {
                $data[$i]["options"] = json_encode($options);
            }
        }
        $this->data[$dataKey] = $data;
    }

    protected function doBeforeOperation(): self
    {
        //Handling options value
        if ($this->IsItMultipleCreation()) {
            $this->changeMultiCreationOptionsDataType();
            return $this;
        }
        $this->data["options"] = json_encode($this->data["options"]);
        return $this;
    }
}
