<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Services\UserManagementServices\UsersExportingServices\SignUpExportingServices\SignUpExporterBuilder;
use App\CustomLibs\ReportingAndBackupSystem\DataFilesInfoManagers\ExportedDataFilesInfoManager\ExportedDataFilesInfoManager;


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
requirePhpFiles(__DIR__ . '/APIs/workSector/HRModule');
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



//This Route For Test
Route::get("test-exporting", function (Request $request) {
    //    $file = $request->file("importedFile");
    //    $service = new SignUpImportingService();
    //    return $service->setUploadedFile($file)->import();
    /**
     * Exporting test  - start
     */
    //    $request->merge(["type" => "csv"]);
    $request->merge(["type" => "json"]);
    //    $request->merge(["type" => "excel"]);
    //    $request->merge(["type" => "pdf"]);
    return (new SignUpExporterBuilder($request))->build()->export();
    /**
     * Exporting test - end
     */
});

//This Route Is Needed To Download The Exported File By FileName parameter sent with email notification
Route::get("exported-file-downloading/{fileName}", function (Request $request, string $fileName) {
    $request->hasValidSignature();
    $exportedDataFilesInfoManager = new ExportedDataFilesInfoManager();
    $fileRealPath = $exportedDataFilesInfoManager->getFileRealPath($fileName);
    if ($fileRealPath) {
        return response()->download($fileRealPath, $fileName);
    }
    return new App\Exceptions\JsonException("The Requested Data File Is Not Found Or Expired !");
})->name("exported-file-downloading");
