<?php

use Illuminate\Support\Facades\Route;


function requirePhpFiles($directoryPath)
{
    foreach (glob($directoryPath . '/*.php') as $fileName) {
        require $fileName;
    }
}
Route::prefix('admin')->group(function () {
    requirePhpFiles(__DIR__ . '/APIs/SystemAdminPanel');
});
requirePhpFiles(__DIR__ . '/APIs/workSector/usersModule');
requirePhpFiles(__DIR__ . '/APIs/workSector/CompanyModule');
requirePhpFiles(__DIR__ . '/APIs/workSector/FinancesModule/CompanyTransactions');
requirePhpFiles(__DIR__ . '/APIs/workSector/FinancesModule/PurchaseInvoices');
requirePhpFiles(__DIR__ . '/APIs/workSector/FinancesModule/AssetsList');
requirePhpFiles(__DIR__ . '/APIs/workSector/FinancesModule/SalesInvoices');
requirePhpFiles(__DIR__ . '/APIs/workSector/FinancesModule/TaxesAndInsurances');
requirePhpFiles(__DIR__ . '/APIs/workSector/ClientsModule');
requirePhpFiles(__DIR__ . '/APIs/workSector/VendorsModule');
requirePhpFiles(__DIR__ . '/APIs/workSector/SystemConfiguration');


Route::get('Unauthorized', function () {
    return response()->json(['message' => 'Unauthorized'], 401);
})->middleware('reqLimit')->name('login');