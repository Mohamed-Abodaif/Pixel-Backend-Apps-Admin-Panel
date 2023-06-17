<?php

namespace App\Http\Controllers\WorkSector\FinancesModule\CompanyTransactions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\WorkSector\FinanceModule\CompanyTransactions\ComapnyTransOutflow;
use App\Http\Requests\WorkSector\FinancesModule\CompanyTransactions\CompanyTransOutflowRequest;
use App\Http\Resources\WorkSector\FinancesModule\CompanyTransactions\CompanyTrasnactionResource;
use App\Services\WorkSector\FinancesModule\CompanyTransactions\CompanyTransOutFlow\CompanyTransOutFlowStoringService;

class CompanyTransOutFlowController extends Controller
{
    public function store(Request $request)
    {
        return (new CompanyTransOutFlowStoringService())->create($request);
    }

    function getTransactions()
    {
        $transctions = ComapnyTransOutflow::with('bank')->get();
        return response()->json([
            "data" => CompanyTrasnactionResource::collection($transctions),
        ]);
    }
}
