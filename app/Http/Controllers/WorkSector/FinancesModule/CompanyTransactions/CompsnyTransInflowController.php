<?php

namespace App\Http\Controllers\WorkSector\FinancesModule\CompanyTransactions;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkSector\FinancesModule\CompanyTransactions\CompanyTransInflowRequest;
use App\Models\WorkSector\FinanceModule\CompanyTransactions\ComapnyTransInflow;
use App\Services\WorkSector\FinancesModule\CompanyTransactions\CompanyTransInFlow\CompanyTransInFlowStoringService;
use Illuminate\Http\Request;

class CompsnyTransInflowController extends Controller
{
    public function store(Request $request)
    {
        return (new CompanyTransInFlowStoringService())->create($request);
    }
}
