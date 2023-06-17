<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonalSector\PersonalTransactions\Inflow\{
    BonusesController,
    CustodiesController
};
use App\Http\Controllers\WorkSector\{
    ClientsModule\ClientsController,
    ClientsModule\ClientOrdersController,
    ClientsModule\ClientQuotationsController,
    VendorsModule\VendorsController,
    VendorsModule\VendorOrdersController,
    UsersModule\EmployeeController,
    FinancesModule\SalesInvoices\SaleInvoicesController,
    FinancesModule\PurchaseInvoices\PurchaseInvoicesController,
    FinancesModule\AssetsList\AssetsController
};
use App\Http\Controllers\WorkSector\SystemConfigurationControllers\DropdownLists\{
    TendersController,
    TaxTypesController,
    CurrenciesController,
    DepartmentsController,
    ExpenseTypesController,
    PaymentTermsController,
    CustodySendersController,
    PaymentMethodsController,
    AssetsCategoriesController,
    BranchesController,
    CountriesController,
    PurchaseOrderTypeController,
    TimeSheetCategoriesController,
    CompanyBankAccountsController
};

Route::prefix('list')->middleware(['auth:api'])->group(function () {
    Route::get('payment-methods', [PaymentMethodsController::class, 'list']);
    Route::get('payment-terms', [PaymentTermsController::class, 'list']);
    Route::get('taxes-types', [TaxTypesController::class, 'list']);
    Route::get('purchase-order-types', [PurchaseOrderTypeController::class, 'list']);
    Route::get('departments', [DepartmentsController::class, 'list']);
    Route::get('assets-categories', [AssetsCategoriesController::class, 'list']);
    Route::get('custody-senders', [CustodySendersController::class, 'list']);
    Route::get('employees', [EmployeeController::class, 'list']);
    Route::get('vendors', [VendorsController::class, 'list']);
    Route::get('clients', [ClientsController::class, 'list']);
    Route::get('currencies', [CurrenciesController::class, 'list']);
    Route::get('expense-types', [ExpenseTypesController::class, 'list']);
    Route::get('purchases-invoices', [PurchaseInvoicesController::class, 'list']);
    Route::get('sales-invoices', [SaleInvoicesController::class, 'list']);
    Route::get('assets', [AssetsController::class, 'list']);
    Route::get('custodies', [CustodiesController::class, 'list']);
    Route::get('bonuses', [BonusesController::class, 'list']);
    Route::get('client-orders', [ClientOrdersController::class, 'list']);
    Route::get('vendor-orders', [VendorOrdersController::class, 'list']);
    Route::get('client-quotations', [ClientQuotationsController::class, 'list']);
    Route::get('client-tenders', [TendersController::class, 'list']);
    Route::get('timesheet-categories', [TimeSheetCategoriesController::class, 'list']);
    Route::get('branches', [BranchesController::class, 'list']);
    Route::get('responsible-persons', [EmployeeController::class, 'list']);
    Route::get('countries', [CountriesController::class, 'list']);
    Route::get('bank-accounts', [CompanyBankAccountsController::class, 'list']);
});
