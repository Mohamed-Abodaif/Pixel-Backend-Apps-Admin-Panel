<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkSector\VendorsModule\VendorOrdersController;

Route::middleware(['auth:api'])->group(function () {
    Route::post('vendor-orders/import', [VendorOrdersController::class, 'importVendorOrders']);
    Route::get('vendor-orders/export', [VendorOrdersController::class, 'exportVendorOrders']);
    Route::resource('vendor-orders', VendorOrdersController::class)->parameters(["vendor-orders" => "vendorOrder"]);
    Route::post('vendor-orders/duplicate/{id}', [VendorOrdersController::class, 'duplicate']);
    Route::put('vendor-orders/status/{id}', [VendorOrdersController::class, 'changeStatus']);
});
