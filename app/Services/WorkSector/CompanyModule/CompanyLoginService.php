<?php

namespace App\Services\WorkSector\CompanyModule;

use Illuminate\Support\Facades\Validator;
use App\Models\WorkSector\CompanyModule\Company;
use Illuminate\Support\Str;

class CompanyLoginService
{
    protected $company;
    function __construct()
    {
        $this->company = new Company;
    }

    function getCompanyById($request)
    {
        Str::startsWith($request->company_id,'CO-') ? $companyId = $request->company_id: $companyId = "CO-$request->company_id";
        return $this->company->whereCompanyId($companyId)->first();
    }
    public function login($request)
    {
        $company = $this->getCompanyById($request);
        if ($company) {
            $response = match ((int) $company->is_active) {
                1 => [
                    "message" => "login successful, Welcome to Company $company->name",
                    "company" => [
                        "name" => $company->name,
                        "logo" => $company->logo
                    ]
                ],
                0 => [
                    "message" => "this company account is inactive",
                    "company" => null
                ],
                default => [
                    "message" => "unknown company status",
                    "company" => null
                ]
            };
            return response()->json($response);
        }
        return response()->json([
            "message" => "Company is not register in our database",
            "company" => null
        ], 422);
    }
}
