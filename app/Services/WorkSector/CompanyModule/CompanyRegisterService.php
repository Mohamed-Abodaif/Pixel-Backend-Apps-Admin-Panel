<?php

namespace App\Services\WorkSector\CompanyModule;

use Exception;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\WorkSector\CompanyModule\Company;
use App\Services\WorkSector\UsersModule\MailService;

class CompanyRegisterService
{
    protected $company;
    function __construct()
    {
        $this->company = new Company;
    }
    public function register($request)
    {
        $data = $request->all();
        $data['registration_status'] = 'pending';
        $this->company->create($data);
    }
    function sendEmailVerificationCode($company)
    {
        // $code = generateVerificationCode($company, 'verification_code');
        return sendEmailVerification($company, null, null, "Your account submited successfully", 'admin_email');
    }

    function sendEmailVerificationToken($company)
    {
        $token = generateVerificationToken($company, 'admin_email');
        $tokenLink = getVerificationLink($token);
        return sendEmailVerification($company, null, null, $tokenLink, 'admin_email');
    }
    function tenant($name)
    {
        $tenant = Tenant::create(['name' => $name]);
        $tenant->domains()->create(['domain' => $name]);
    }
    function createAccount($request)
    {
        try {
            DB::beginTransaction();
            $this->register($request);
            $company = Company::whereAdminEmail($request->admin_email)->first();
            // $this->tenant($company->name);
            return $this->sendEmailVerificationToken($company);
            if ($this->sendEmailVerificationToken($company)) {
                DB::commit();
                return response()->json([
                    "message" => "Company Account Created Successfully"
                ]);
            } else {
                DB::rollback();
                return response()->json([
                    "message" => "Failed To Send Verification token ... Email Not Found !"
                ]);
            }
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                "message" => $e->getMessage()
            ]);
        }
    }
}
