<?php

namespace App\Services\SystemAdminPanel\Company;

use Illuminate\Support\Facades\DB;
use App\Models\WorkSector\CompanyModule\Company;

class CompanyChangeListStatusService
{
    protected $company, $request;
    function __construct($request)
    {
        $this->company = Company::find($request->id);
        $this->request = $request;
    }


    function updateCompanyStatus()
    {
        $company = $this->company ?? notFound();
        $company->is_active = $this->request->is_active;
        $company->save();
        return $company->is_active;
    }
    function checkCompanyStatus()
    {
        if ($this->company->is_active == true) {
            return "company status is active";
        } else {
            return "company status is inactive";
        }
    }
    function update()
    {
        $this->updateCompanyStatus();
        $this->checkCompanyStatus();
        return response()->json([
            "message" => $this->checkCompanyStatus(),
        ]);
    }
}
