<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkSector\FinancesModule\PurchaseInvoices\PurchaseInvoicesController;

Route::middleware(['auth:api'])->group(function () {
    Route::post('purchase-invoices/import', [PurchaseInvoicesController::class, 'importPurchaseInvoices']);
    Route::get('purchase-invoices/export', [PurchaseInvoicesController::class, 'exportPurchaseInvoices']);
    Route::resource('/purchase-invoices', PurchaseInvoicesController::class);
    Route::post('purchase-invoices/duplicate/{id}', [PurchaseInvoicesController::class, 'duplicate']);
    Route::put('purchase-invoices/status/{id}', [PurchaseInvoicesController::class, 'changeStatus']);
});
