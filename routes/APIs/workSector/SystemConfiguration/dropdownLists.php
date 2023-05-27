<?php

use App\Http\Controllers\GeneralControllers\ExcelFormateController;
use App\Http\Controllers\WorkSector\FinancesModule\TaxesAndInsurances\InsurancTypeController;
use App\Http\Controllers\WorkSector\SystemConfigurationControllers\{
    DropdownLists\AreasController,
    DropdownLists\BranchesController,
    DropdownLists\CurrenciesController,
    DropdownLists\CustodySendersController,
    DropdownLists\DepartmentsController,
    DropdownLists\ExpenseTypesController,
    DropdownLists\FeesController,
    DropdownLists\OfficalRecieptIssuersController,
    DropdownLists\PaymentMethodsController,
    DropdownLists\PaymentTermsController,
    DropdownLists\AssetsCategoriesController,
    DropdownLists\PurchaseOrderTypeController,
    RolesAndPermissions\RolesController,
    DropdownLists\TaxTypesController,
    DropdownLists\CompanyTreasuriesController,
    DropdownLists\CompanyBankAccountsController,
    DropdownLists\TendersController,
    DropdownLists\TimeSheetCategoriesController,
    DropdownLists\TimeSheetSubCategoriesController
};
use Illuminate\Support\Facades\Route;


Route::prefix('system-configs')->middleware(['auth:api'])->group(function () {

    // Route::resource('insurance-types', InsurancTypeController::class);
    // Route::get('insurance-types/all', [InsurancTypeController::class,'list']);
    Route::get('download/excel-fromate', [ExcelFormateController::class, 'downloadExcelFromate']);


    Route::resource('payment-methods', PaymentMethodsController::class)->parameters(["payment-methods" => "method"]);
    Route::post('payment-methods/import', [PaymentMethodsController::class, 'importPaymentMethods']);
    Route::get('payment-methods/export', [PaymentMethodsController::class, 'exportPaymentMethods']);

    Route::resource('payment-terms', PaymentTermsController::class)->parameters(["payment-terms" => "term"]);
    Route::post('payment-terms/import', [PaymentTermsController::class, 'importPaymentTerms']);
    Route::get('payment-terms/export', [PaymentTermsController::class, 'exportPaymentTerms']);

    Route::resource('taxes-types', TaxTypesController::class)->parameters(["taxes-types" => "type"]);
    Route::post('taxes-types/import', [TaxTypesController::class, 'importTaxTypes']);
    Route::get('taxes-types/export', [TaxTypesController::class, 'exportTaxTypes']);

    Route::resource('client-tenders', TendersController::class)->parameters(["client-tenders" => "tender"]);

    Route::post('purchase-order-types/import', [PurchaseOrderTypeController::class, 'importPurchaseOrderTypes']);
    Route::get('purchase-order-types/export', [PurchaseOrderTypeController::class, 'exportPurchaseOrderTypes']);
    Route::resource('purchase-order-types', PurchaseOrderTypeController::class);


    Route::resource('departments', DepartmentsController::class);
    Route::post('departments/import', [DepartmentsController::class, 'importDepartments']);
    Route::get('departments/export', [DepartmentsController::class, 'exportDepartments']);

    // Route::resource('roles', RolesController::class);
    // Route::get('roles/all', [RolesController::class,'list']);

    Route::resource('assets-categories', AssetsCategoriesController::class)->parameters(["assets-categories" => "category"]);
    Route::post('assets-categories/import', [AssetsCategoriesController::class, 'importAssetsCategories']);
    Route::get('assets-categories/export', [AssetsCategoriesController::class, 'exportAssetsCategories']);


    Route::resource('custody-senders', CustodySendersController::class)->parameters(["custody-senders" => "sender"]);
    Route::post('custody-senders/import', [CustodySendersController::class, 'importCustodySenders']);
    Route::get('custody-senders/export', [CustodySendersController::class, 'exportCustodySenders']);

    Route::resource('expense-types', ExpenseTypesController::class)->parameters(["expense-types" => "type"]);
    Route::post('expense-types/import', [ExpenseTypesController::class, 'importExpenseTypes']);
    Route::get('expense-types/export', [ExpenseTypesController::class, 'exportExpenseTypes']);

    Route::resource('currencies', CurrenciesController::class)->only(["index", "update"]);
    Route::post('currencies/set-main/{currency}', [CurrenciesController::class, 'setMainCurrency']);
    Route::post('currencies/import', [CurrenciesController::class, 'importCurrencies']);
    Route::get('currencies/export', [CurrenciesController::class, 'exportCurrencies']);

    Route::resource('timesheet-categories', TimeSheetCategoriesController::class)->parameters(["timesheet-categories" => "category"]);
    Route::put('timesheet-categories/status/{id}', [TimeSheetCategoriesController::class, 'changeStatus']);

    Route::resource('timesheet-sub-categories', TimeSheetSubCategoriesController::class)->parameters(["timesheet-sub-categories" => "category"]);
    Route::put('timesheet-sub-categories/status/{id}', [TimeSheetCategoriesController::class, 'changeStatus']);
    Route::get('timesheet-filters', [TimeSheetCategoriesController::class, 'timesheetFilters']);

    Route::resource('company-bank-accounts', CompanyBankAccountsController::class);
    Route::resource('company-treasuries', CompanyTreasuriesController::class);
    Route::resource('fees', FeesController::class);
    Route::resource('offical-reciept-issuers', OfficalRecieptIssuersController::class);
    Route::resource('branches', BranchesController::class);
    Route::resource('areas', AreasController::class);
});
