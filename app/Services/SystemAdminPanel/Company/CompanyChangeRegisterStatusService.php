<?php

namespace App\Services\SystemAdminPanel\Company;

use Illuminate\Support\Facades\DB;
use App\Models\WorkSector\CompanyModule\Company;

class CompanyChangeRegisterStatusService
{
    protected $company, $request;
    function __construct($request)
    {
        $this->company = Company::whereCompanyId($request->company_id)->first();
        $this->request = $request;
    }

    function generateCompanyId()
    {
        $companyId = 'CO-' . random_int(100000, 999999);
        if (!$this->company->company_id) {
            if (codeExists($companyId, 'company_id')) {
                return $this->generateCompanyId();
            }
            return $companyId;
        }
        return $this->company->company_id;
    }
    function updateCompanyStatus()
    {
        $company = $this->company ?? notFound();
        $companyId = $this->generateCompanyId();
        $company->registration_status = $this->request->registration_status;
        $company->company_id = $companyId;
        $company->save();
        return $company->status;
    }
    function sendEmail()
    {
        $companyId = explode('-', $this->company->company_id);
        $subject = "Congratulations (Company Account Approval)";
        $message = "<br>congratulations , your company account has been approved welcome to your company , you can use below
        company id to login to your company account : <br><p> {$companyId[1]}</p> <br>Sincerely <br> ------------ <br> IGS Support Team ";
        sendEmailVerification($this->company, $subject, $message, null, 'admin_email');
    }
    function checkCompanyStatus()
    {
        if ($this->company->registration_status == "approved") {
            return $this->sendEmail();
        }
    }
    function update()
    {
        $this->updateCompanyStatus();
        $this->checkCompanyStatus();
        return response()->json([
            "message" => "Company Account Status Has been Updated",
        ]);
    }
}
