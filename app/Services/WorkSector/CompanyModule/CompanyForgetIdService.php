<?php

namespace App\Services\WorkSector\CompanyModule;

use Illuminate\Support\Facades\DB;
use App\Models\WorkSector\CompanyModule\Company;

class CompanyForgetIdService
{
    protected $request;
    function __construct($request)
    {
        $this->request = $request;
    }

    function sendEmail()
    {
        $company = Company::whereAdminEmail($this->request->admin_email)->first() ?? die("Couldn't find company email");
        $companyId = explode('-', $company->company_id);
        $subject = "Company Account Id";
        $message = "kindly use this id to login for
        your company account <br><p> {$companyId[1]}</p> <br>Sincerely <br> ------------ <br> IGS Support Team</p>";
        sendEmailVerification($company, $subject, $message, null, 'admin_email');
    }
    function sendId()
    {
        $this->sendEmail();
        return response()->json([
            "message" => "Company Account Id Has been Sent",
        ]);
    }
}
