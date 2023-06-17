<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\Departments;

use App\Models\WorkSector\SystemConfigurationModels\Department;
use App\Http\Requests\WorkSector\SystemConfigurations\UpdatingRequest;

class UpdatingDepartmentRequest extends UpdatingRequest
{
    protected function getModelName(): string
    {
        return Department::class;
    }

    protected function getTableName(): string
    {
        return "departments";
    }

    public function messages()
    {
        return [
            "name.unique" => "Department's Name Is Already Stored In Our Database !",
            "status.boolean" =>  "Department's Status  Must Be Boolean",
        ];
    }
}