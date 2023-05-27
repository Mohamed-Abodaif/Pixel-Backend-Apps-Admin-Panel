<?php

namespace App\Services\WorkSector\CompanyModule;

use Exception;
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

    function createAccount($request)
    {
        try {
            DB::beginTransaction();
            $this->register($request);
            $company = Company::whereAdminEmail($request->admin_email)->first();
                DB::commit();
                return response()->json([
                    "message" => "Company Account Created Successfully"
                ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                "message" => $e->getMessage()
            ]);
        }
    }
}
