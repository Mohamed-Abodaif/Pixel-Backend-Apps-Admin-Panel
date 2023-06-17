<?php

namespace App\Http\Controllers\WorkSector\CompanyModule;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WorkSector\CompanyModule\Company;
use App\Services\GeneralServices\EmailVerifyService;
use App\Services\WorkSector\CompanyModule\{
    CompanyLoginService,
    CompanyRegisterService,
    CompanyStatusService,
    CompanyForgetIdService
};
use App\Http\Requests\WorkSector\CompanyModule\{
    CompanyRequest,
    CheckStatusRequest,
    CompanyLoginRequest
};

class CompanyAuthController extends Controller
{
    public function register(CompanyRequest $request)
    {

        return (new CompanyRegisterService())->createAccount($request);
    }

    public function login(CompanyLoginRequest $request)
    {
        return (new CompanyLoginService())->login($request);
    }

    public function verifyEmail(Request $request)
    {
        // return response()->json($request);
        return (new EmailVerifyService())->checkEmail($request, new Company);
    }
    public function checkStatus(CheckStatusRequest $request)
    {
        return (new CompanyStatusService())->checkStatus($request);
    }
    public function forgetId(CheckStatusRequest $request)
    {
        return (new CompanyForgetIdService($request))->sendId();
    }
}
