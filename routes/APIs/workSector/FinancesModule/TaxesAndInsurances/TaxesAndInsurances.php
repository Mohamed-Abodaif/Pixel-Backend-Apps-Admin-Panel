<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkSector\FinancesModule\TaxesAndInsurances\{TaxExpenseController, InsuranceExpenseController};
use App\Http\Controllers\PersonalSector\PersonalTransactions\OutFlow\ExchangeExpenseController;

Route::middleware(['auth:api'])->group(function () {
    Route::resource('/insurance-expenses', InsuranceExpenseController::class);
    Route::resource('/taxes-expenses', TaxExpenseController::class);
    Route::resource('/exchange-expenses', ExchangeExpenseController::class);
});
