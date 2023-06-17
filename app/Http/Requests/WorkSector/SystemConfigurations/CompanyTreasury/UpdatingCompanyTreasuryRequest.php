<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\CompanyTreasury;

use App\Models\WorkSector\SystemConfigurationModels\CompanyTreasury;
use App\Http\Requests\WorkSector\SystemConfigurations\UpdatingRequest;

class UpdatingCompanyTreasuryRequest extends UpdatingRequest
{
    protected function getModelName(): string
    {
        return CompanyTreasury::class;
    }

    protected function getTableName(): string
    {
        return "company_treasuries";
    }

    public function messages()
    {
        return [
            "name.unique" => "CompanyTreasury's Name Is Already Stored In Our Database !",
            "status.boolean" =>  "CompanyTreasury's Status  Must Be Boolean",
        ];
    }
}