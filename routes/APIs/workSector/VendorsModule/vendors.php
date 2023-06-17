<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkSector\VendorsModule\VendorsController;

Route::middleware(['auth:api'])->group(function () {

    Route::post('vendors/import', [VendorsController::class, 'importVendors']);
    Route::get('vendors/export', [VendorsController::class, 'exportVendors']);
    Route::resource('/vendors', VendorsController::class)->parameters(["vendors" => "vendor"]);
    Route::post('vendors/duplicate/{id}', [VendorsController::class, 'duplicate']);
    Route::put('vendors/status/{id}', [VendorsController::class, 'changeStatus']);
});
