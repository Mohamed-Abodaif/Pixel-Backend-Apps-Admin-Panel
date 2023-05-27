<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkSector\FinancesModule\SalesInvoices\{SaleInvoicesController, SalesInvoiceStatusController};

Route::middleware(['auth:api'])->group(function () {
    Route::post('approve', [SalesInvoiceStatusController::class, 'approve']);
    Route::post('sent', [SalesInvoiceStatusController::class, 'sendInvoice']);
    Route::post('approve-in-portal', [SalesInvoiceStatusController::class, 'approveInPortal']);
});

Route::middleware(['auth:api'])->group(function () {
    Route::post('sales-invoices/import', [SaleInvoicesController::class, 'importSalesInvoices']);
    Route::get('sales-invoices/export', [SaleInvoicesController::class, 'exportSalesInvoices']);
    Route::resource('/sales-invoices', SaleInvoicesController::class);
    Route::post('sales-invoices/duplicate/{id}', [SaleInvoicesController::class, 'duplicate']);
    Route::put('sales-invoices/status/{id}', [SaleInvoicesController::class, 'changeStatus']);
});
