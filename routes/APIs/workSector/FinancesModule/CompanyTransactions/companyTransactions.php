<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkSector\FinancesModule\CompanyTransactions\CompanyTransOutFlowController;

Route::prefix('finances-modules')->group(function () {
    Route::post('company-out-trans/add', [CompanyTransOutFlowController::class, 'store']);
    Route::get('company-out-trans/show', [CompanyTransOutFlowController::class, 'getTransactions']);
});
