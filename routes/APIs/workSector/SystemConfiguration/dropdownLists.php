<?php

use App\Http\Controllers\GeneralControllers\ExcelFormateController;
use App\Http\Controllers\WorkSector\FinancesModule\TaxesAndInsurances\InsurancTypeController;
use App\Http\Controllers\WorkSector\SystemConfigurationControllers\DropdownLists\{
    AreasController,
    BranchesController,
    CurrenciesController,
    CustodySendersController,
    DepartmentsController,
    ExpenseTypesController,
    FeesController,
    OfficalRecieptIssuersController,
    PaymentMethodsController,
    PaymentTermsController,
    AssetsCategoriesController,
    PurchaseOrderTypeController,
    TaxTypesController,
    CompanyTreasuriesController,
    CompanyBankAccountsController,
    TendersController,
    TimeSheetCategoriesController,
    TimeSheetSubCategoriesController,
    MeasurementUnitesController,
    ProductsCategoriesController,
    ServiceCategoriesController
};
use App\Http\Controllers\WorkSector\SystemConfigurationControllers\RolesAndPermissions\RolesController;
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

    Route::resource('taxes-types', TaxTypesController::class)->parameters(["taxes_types" => "type"]);
    Route::post('taxes-types/import', [TaxTypesController::class, 'importTaxTypes']);
    Route::get('taxes-types/export', [TaxTypesController::class, 'exportTaxTypes']);

    Route::resource('client-tenders', TendersController::class)->parameters(["client_tenders" => "tender"]);

    Route::post('purchase-order-types/import', [PurchaseOrderTypeController::class, 'importPurchaseOrderTypes']);
    Route::get('purchase-order-types/export', [PurchaseOrderTypeController::class, 'exportPurchaseOrderTypes']);
    Route::resource('purchase-order-types', PurchaseOrderTypeController::class);


    Route::resource('departments', DepartmentsController::class)->parameters(["departments" => "department"]);
    Route::post('departments/import', [DepartmentsController::class, 'importDepartments']);
    Route::get('departments/export', [DepartmentsController::class, 'exportDepartments']);

    // Route::resource('roles', RolesController::class);
    // Route::get('roles/all', [RolesController::class,'list']);

    Route::resource('assets-categories', AssetsCategoriesController::class)->parameters(["assets-categories" => "category"]);
    Route::post('assets-categories/import', [AssetsCategoriesController::class, 'importAssetsCategories']);
    Route::get('assets-categories/export', [AssetsCategoriesController::class, 'exportAssetsCategories']);

    Route::resource('product-categories', ProductsCategoriesController::class)->parameters(["product-categories" => "category"]);
    Route::post('product-categories/import', [ProductsCategoriesController::class, 'importProductCategories']);
    Route::get('product-categories/export', [ProductsCategoriesController::class, 'exportProductCategories']);

    Route::resource('service-categories', ServiceCategoriesController::class)->parameters(["service-categories" => "category"]);
    Route::post('service-categories/import', [ServiceCategoriesController::class, 'importServiceCategories']);
    Route::get('service-categories/export', [ServiceCategoriesController::class, 'exportServiceCategories']);

    Route::resource('measurement-units', MeasurementUnitesController::class)->parameters(["measurement-units" => "measurementUnit"]);
    Route::post('measurement-units/import', [MeasurementUnitesController::class, 'importServiceCategories']);
    Route::get('measurement-units/export', [MeasurementUnitesController::class, 'exportServiceCategories']);

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

    Route::resource('timesheet-sub-categories', TimeSheetSubCategoriesController::class)->parameters(["timesheet-sub-categories" => "subcategory"]);
    Route::put('timesheet-sub-categories/status/{id}', [TimeSheetCategoriesController::class, 'changeStatus']);
    Route::get('timesheet-filters', [TimeSheetCategoriesController::class, 'timesheetFilters']);

    Route::resource('company-bank-accounts', CompanyBankAccountsController::class)->parameters(["company-bank-accounts" => "companyBankAccount"]);
    Route::resource('company-treasuries', CompanyTreasuriesController::class)->parameters(["company-treasuries" => "companyTreasury"]);
    Route::resource('taxes-official-fees', FeesController::class)->parameters(["taxes-official-fees" => "taxes-official-fees"]);
    Route::resource('offical-receipt-issuers', OfficalRecieptIssuersController::class)->parameters(["offical-receipt-issuers" => "offical-receipt-issuers"]);
    Route::resource('branches', BranchesController::class)->parameters(["branches" => "branch"]);
    Route::resource('areas', AreasController::class);
});
