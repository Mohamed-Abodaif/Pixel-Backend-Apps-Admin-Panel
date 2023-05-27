<?php

namespace App\Services\SystemAdminPanel\Company;

use Illuminate\Support\Facades\DB;
use App\Models\WorkSector\CompanyModule\Company;

use function PHPUnit\Framework\isEmpty;

class CompanyUpdateService
{
    protected $company, $request;
    function __construct($request)
    {
        $this->company = Company::find($request->id);
        $this->request = $request->except($this->company->exceptData);
    }

    function updateCompany()
    {
        $company = $this->company ?? notFound();
        $data = $this->request;
        $data['logo'] = request()->logo ?? $company->logo;
        $company->update($data);
        return $company;
    }
    function update()
    {
        if ($this->updateCompany()) {
            return response()->json([
                "message" => "company has been updated successfully",
            ]);
        } else {
            return response()->json([
                "message" => "company has not been updated successfully",
            ]);
        }
    }
}
