<?php

namespace App\Services\WorkSector\SystemConfigurationServices\DropdownLists\TimesheetSubCategoriesOperations;

use App\CustomLibs\ValidatorLib\Validator;
use App\Http\Requests\WorkSector\SystemConfigurations\TimesheetSubCategories\StoringTimesheetSubCategoryRequest;
use App\Models\WorkSector\SystemConfigurationModels\TimeSheetSubCategory;
use App\Services\WorkSector\Interfaces\NeedToStoreDateFields;
use App\Services\WorkSector\SystemConfigurationServices\SystemConfigurationStoringService;
use Exception;

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
        return ['name', 'timesheet_category_id', 'options', 'status'];
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
        $key = $this->getRequestDataKey();
        $data = $this->data[$key] ?? [];

        for ($i = 0; $i < count($data); $i++) {
            $options = isset($data[$i]) && isset($data[$i]["options"]) ? $data[$i]["options"] : [];
            if (is_array($options)) {
                $data[$i]["options"] = json_encode($options);
            }
        }
        $this->data[$key] = $data;
    }


    //TODO: must review this fun
    protected function doBeforeOperation(): self
    {

        // Handling multiple options value
        if ($this->IsItMultipleCreation()) {
            $this->changeMultiCreationOptionsDataType();
            return $this;
        }
        // Handling single options value
        $key = $this->getRequestDataKey();
        if (isset($this->data[$key][0])) {
            $oldData = $this->data[$key][0];
            $oldData["options"] = json_encode($oldData["options"]);
            $this->data = $oldData;
        }
        return $this;
    }

}
