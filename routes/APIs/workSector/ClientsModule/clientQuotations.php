<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkSector\ClientsModule\ClientQuotationsController;

Route::middleware(['auth:api'])->group(function () {
    Route::get('client-quotations/export', [ClientQuotationsController::class, 'exportClientQuotations']);
    Route::post('client-quotations/import', [ClientQuotationsController::class, 'importClientQuotations']);
    Route::resource('/client-quotations', ClientQuotationsController::class);
    Route::post('client-quotations/duplicate/{id}', [ClientQuotationsController::class, 'duplicate']);
    Route::put('client-quotations/status/{id}', [ClientQuotationsController::class, 'changeStatus']);
});
