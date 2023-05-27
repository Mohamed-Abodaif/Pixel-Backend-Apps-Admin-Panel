<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkSector\FinancesModule\TaxesAndInsurances\{TaxExpenseController, InsuranceExpenseController};

Route::middleware(['auth:api'])->group(function () {
    Route::resource('/insurance-expenses', InsuranceExpenseController::class);
});

Route::middleware(['auth:api'])->group(function () {
    Route::resource('/taxes-expenses', TaxExpenseController::class);
});
