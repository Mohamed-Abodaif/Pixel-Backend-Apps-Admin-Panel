<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkSector\FinancesModule\CompanyTransactions\{CompsnyTransInflowController, CompanyTransOutFlowController};

Route::prefix('finances-modules')->group(function () {
    Route::post('company-out-trans/add', [CompanyTransOutFlowController::class, 'store']);
    Route::get('company-out-trans/show', [CompanyTransOutFlowController::class, 'getTransactions']);
    Route::post('company-in-trans/add', [CompsnyTransInflowController::class, 'store']);
    Route::get('company-in-trans/show', [CompsnyTransInflowController::class, 'getTransactions']);
});
