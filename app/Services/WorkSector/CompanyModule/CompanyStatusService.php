<?php

namespace App\Services\WorkSector\CompanyModule;

use App\Models\WorkSector\CompanyModule\Company;
use Illuminate\Support\Facades\Validator;

class CompanyStatusService
{

    protected $company;
    function __construct()
    {
        $this->company = new Company;
    }

    function requestRules($request)
    {
        Validator::make($request->all(), $request->rules());
    }

    function isVerified($request)
    {
        $verified = $this->company->whereAdminEmail($request->admin_email)->first();
        return $verified->email_verified_at ?? null;
    }

    function registrationStatus($request)
    {
        return $this->company->whereAdminEmail($request->admin_email)->first()->registration_status;
    }
    public function checkStatus($request)
    {
        if ($this->company->whereAdminEmail($request->admin_email)->first() == null) {
            return response()->json([
                "message" => "Your email is not registered in our database"
            ], 422);
        } elseif (!$this->isVerified($request)) {
            return response()->json([
                "message" => "Your company registerd email has not been verified yet"
            ]);
        } elseif ($this->registrationStatus($request) == "pending") {
            return response()->json([
                "message" => "Your company account has not been approved yet"
            ], 422);
        } elseif ($this->registrationStatus($request) == "rejected") {
            return response()->json([
                "message" => "Your company account has been rejected"
            ], 422);
        } elseif ($this->registrationStatus($request) == "approved" && $this->isVerified($request)) {
            return response()->json([
                "message" => "Your company account has been approved , kindly check your email for company id"
            ], 422);
        }
    }
}
