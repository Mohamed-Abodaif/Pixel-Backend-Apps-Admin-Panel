<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkSector\ClientsModule\PurchaseRequestsController;

// Route::middleware(['auth:api'])->group(function () {
Route::get('client-purchase-requests/export', [PurchaseRequestsController::class, 'exportPurchaseRequests']);
Route::post('client-purchase-requests/import', [PurchaseRequestsController::class, 'importPurchaseRequests']);
Route::resource('client-purchase-requests', PurchaseRequestsController::class);
Route::post('client-purchase-requests/duplicate/{id}', [PurchaseRequestsController::class, 'duplicate']);
Route::put('client-purchase-requests/status/{id}', [PurchaseRequestsController::class, 'changeStatus']);
// });
