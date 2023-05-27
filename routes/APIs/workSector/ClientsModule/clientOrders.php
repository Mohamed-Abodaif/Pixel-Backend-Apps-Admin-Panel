<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkSector\ClientsModule\ClientOrdersController;

Route::middleware(['auth:api'])->group(function () {
    Route::post('client-orders/import', [ClientOrdersController::class, 'importClientOrders']);
    Route::get('client-orders/export', [ClientOrdersController::class, 'exportClientsOrders']);
    Route::resource('client-orders', ClientOrdersController::class);
    Route::post('client-orders/duplicate/{id}', [ClientOrdersController::class, 'duplicate']);
    Route::put('client-orders/status/{id}', [ClientOrdersController::class, 'changeStatus']);
});
