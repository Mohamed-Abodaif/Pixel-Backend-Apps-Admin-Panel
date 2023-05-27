<?php

namespace App\Http\Controllers\WorkSector\FinancesModule\CompanyTransactions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\WorkSector\FinanceModule\CompanyTransactions\ComapntTransOutflow;
use App\Http\Requests\WorkSector\FinancesModule\CompanyTransactions\CompanyTransactionRequest;
use App\Http\Resources\WorkSector\FinancesModule\CompanyTransactions\CompanyTrasnactionResource;

class CompanyTransOutFlowController extends Controller
{
    function store(CompanyTransactionRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = 5;
        $transaction = ComapntTransOutflow::create($data);
        return response()->json([
            "message" => "transaction created"
        ], 200);
    }

    function getTransactions()
    {
        $transctions = ComapntTransOutflow::with('bank')->get();
        return response()->json([
            "data" => CompanyTrasnactionResource::collection($transctions),
        ]);
    }
}
