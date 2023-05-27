<?php

namespace App\Http\Controllers\SystemAdminPanel\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\SystemAdminPanel\Company\ChangeCompanyListStatusRequest;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\WorkSector\CompanyModule\Company;
use App\Services\SystemAdminPanel\Company\CompanyChangeRegisterStatusService;
use App\Services\SystemAdminPanel\Company\CompanyChangeListStatusService;
use App\Http\Requests\SystemAdminPanel\Company\ChangeCompanyStatusRequest;
use App\Http\Requests\WorkSector\CompanyModule\CompanyRequest;
use App\Services\SystemAdminPanel\Company\CompanyUpdateService;

class CompanyManagementController extends Controller
{
    function signupList()
    {
        $compnay = QueryBuilder::for(Company::class)
            ->allowedFilters([
                'first_name',
                'last_name',
                'admin_email',
                'name',
                'id',
                'registration_status'
            ])
            ->where('registration_status', 'pending')->get();

        return response()->json([
            "data" => $compnay
        ]);
    }

    function companyList()
    {
        $compnay = QueryBuilder::for(Company::class)
            ->allowedFilters(['first_name', 'last_name', 'admin_email', 'name', 'id', 'registration_status'])
            ->where('registration_status', '!=', 'pending')->get();

        return response()->json([
            "data" => $compnay
        ]);
    }

    function updateRegisterStatus(ChangeCompanyStatusRequest $request)
    {
        return (new CompanyChangeRegisterStatusService($request))->update();
    }

    function updateCompanyListStatus(ChangeCompanyListStatusRequest $request)
    {
        return (new CompanyChangeListStatusService($request))->update();
    }

    function updateCompany(CompanyRequest $request)
    {
        return (new CompanyUpdateService($request))->update();
    }

    function hide($id)
    {
        Company::destroy($id);
        return response()->json([
            "message" => "company account has been hidden",
        ]);
    }

    function delete($id)
    {
        Company::withTrashed()->find($id)->forceDelete();
        return response()->json([
            "message" => "company account has been deleted",
        ]);
    }

    function companyHiddenList()
    {
        $compnay = QueryBuilder::for(Company::class)
            ->allowedFilters(['first_name', 'last_name', 'admin_email', 'name', 'id', 'registration_status'])
            ->where('registration_status', '!=', 'pending')->onlyTrashed()->get();

        return response()->json([
            "data" => $compnay
        ]);
    }
}
