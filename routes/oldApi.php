<?php

use App\CustomLibs\ExportersManagement\ExportedFilesProcessor\ExportedFilesProcessor;
use App\Http\Controllers\AssetsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BonusesController;
use App\Http\Controllers\ClientOrdersController;
use App\Http\Controllers\ClientQuotationsController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\CustodiesController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeesExpensesController;
use App\Http\Controllers\EmployeesTimesheetController;
use App\Http\Controllers\ExcelFormateController;
use App\Http\Controllers\ExchangeExpenseController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseDiscussionController;
use App\Http\Controllers\InsuranceExpenseController;
use App\Http\Controllers\PurchaseInvoicesController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SaleInvoicesController;
use App\Http\Controllers\SalesInvoiceStatusController;
use App\Http\Controllers\SignUpController;
use App\Http\Controllers\WorkSector\SystemConfigurationControllers\{
    AreasController,
    AssetsCategoriesController,
    BranchesController,
    CompanyBankAccountsController,
    CompanyTreasuriesController,
    CurrenciesController,
    CustodySendersController,
    DepartmentsController,
    ExpenseTypesController,
    FeesController,
    PaymentMethodsController,
    PaymentTermsController,
    TaxTypesController,
    TendersController,
    TimeSheetCategoriesController,
    TimeSheetSubCategoriesController,
    PurchaseOrderTypeController
};
use App\Http\Controllers\TaxExpenseController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorOrdersController;
use App\Http\Controllers\VendorsController;
use App\Http\Controllers\WorkSector\FinancesModule\CompanyTranactions\CompanyTransactionController;
use App\Models\User;
use App\Services\UserManagementServices\UsersExportingServices\SignUpExportingServices\SignUpExporterBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;
use Spatie\QueryBuilder\QueryBuilder;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::resource('countries', CountriesController::class)->only('index');
Route::domain('{username}.' . env('APP-URL'))->group(function () {
    Route::get('post/{id}', function ($username, $id) {
        return 'User ' . $username . ' is trying to read post ' . $id;
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:api'])->group(function () {
    Route::group(['prefix' => 'list'], function () {
        Route::get('roles', [RolesController::class, 'list']);
    });

    Route::put('roles/{role}/status', [RolesController::class, 'switchRole']);
    Route::get('all-permissions', [RolesController::class, 'allPermission']);
    Route::resource('roles', RolesController::class)->except(['edit']);
});


Route::prefix('user')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forget-password', [AuthController::class, 'forgetPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
    Route::post('resend-verification-token-to-email', [AuthController::class, 'resendVerificationTokenToUserEmail']);
    Route::post('re-check-verification-code', [AuthController::class, 'checkVerificationCodeByEmail']);

    Route::middleware(['auth:api'])->group(function () {
        Route::post('resend-verification-token-to-user', [AuthController::class, 'resendVerificationTokenToAuthenticatedUser']);
        Route::post('check-verification-code', [AuthController::class, 'checkVerificationCodeForAuthenticatedUser']);
        Route::post('change-email', [AuthController::class, 'changeEmail']);
        Route::get('profile', [UserController::class, 'getCurrentUserProfile']);
        Route::put('profile', [UserController::class, 'updateProfile']);
        Route::put('change-password', [UserController::class, 'changePassword']);
        Route::get('details/{user}', [UserController::class, 'getUser']);
        Route::delete("delete-user/{user}",   [UserController::class, 'destroy']);
        Route::get('me', [UserController::class, 'checkAuth']);
        Route::put('update-user-role/{user}', [UserController::class, 'updateUserRole']);
        Route::put('change-account-status/{user}', [UserController::class, 'changeAccountStatus']);

        //        Route::resource('signup-list', UserController::class)->except("index" , "show" , 'create' , 'edit' , 'store');
        //        Route::get('signup-list/{user}/show', [UserController::class , ""]);
    });
});
Route::middleware(['auth:api'])->group(function () {
    Route::resource('signup-list', SignUpController::class)->except("update");
    Route::put('signup-list/update/{user}', [SignUpController::class, 'update']);
    Route::delete('signup-list/{user}', [UserController::class, 'destroy']);
    Route::put('signup-list/status/{user}', [SignUpController::class, 'changeAccountStatus']);
});


Route::middleware(['auth:api'])->group(function () {
    Route::resource('employees', EmployeeController::class)->except("destroy", "update");
    Route::put('employees/update/{user}', [EmployeeController::class, 'update']);
    Route::delete('employees/{user}', [UserController::class, 'destroy']);
    Route::put('employees/status/{user}', [EmployeeController::class, 'changeAccountStatus']);
});


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

Route::middleware(['auth:api'])->group(function () {
    Route::post('/clients/import', [ClientsController::class, 'importClients']);
    Route::get('/clients/export', [ClientsController::class, 'exportClients']);
    Route::resource('/clients', ClientsController::class);
    Route::post('/clients/duplicate/{id}', [ClientsController::class, 'duplicate']);
    Route::put('/clients/status/{id}', [ClientsController::class, 'changeStatus']);
});

Route::middleware(['auth:api'])->group(function () {
    Route::post('client-orders/import', [ClientOrdersController::class, 'importClientOrders']);
    Route::get('client-orders/export', [ClientOrdersController::class, 'exportClientsOrders']);
    Route::resource('client-orders', ClientOrdersController::class);
    Route::post('client-orders/duplicate/{id}', [ClientOrdersController::class, 'duplicate']);
    Route::put('client-orders/status/{id}', [ClientOrdersController::class, 'changeStatus']);
});


Route::middleware(['auth:api'])->group(function () {
    Route::get('client-quotations/export', [ClientQuotationsController::class, 'exportClientQuotations']);
    Route::post('client-quotations/import', [ClientQuotationsController::class, 'importClientQuotations']);
    Route::resource('/client-quotations', ClientQuotationsController::class);
    Route::post('client-quotations/duplicate/{id}', [ClientQuotationsController::class, 'duplicate']);
    Route::put('client-quotations/status/{id}', [ClientQuotationsController::class, 'changeStatus']);
});


Route::middleware(['auth:api'])->group(function () {
    Route::post('vendor-orders/import', [VendorOrdersController::class, 'importVendorOrders']);
    Route::get('vendor-orders/export', [VendorOrdersController::class, 'exportVendorOrders']);
    Route::resource('vendor-orders', VendorOrdersController::class);
    Route::post('vendor-orders/duplicate/{id}', [VendorOrdersController::class, 'duplicate']);
    Route::put('vendor-orders/status/{id}', [VendorOrdersController::class, 'changeStatus']);
});

Route::middleware(['auth:api'])->group(function () {

    Route::post('vendors/import', [VendorsController::class, 'importVendors']);
    Route::get('vendors/export', [VendorsController::class, 'exportVendors']);
    Route::resource('/vendors', VendorsController::class);
    Route::post('vendors/duplicate/{id}', [VendorsController::class, 'duplicate']);
    Route::put('vendors/status/{id}', [VendorsController::class, 'changeStatus']);
});

Route::middleware(['auth:api'])->group(function () {
    Route::post('purchase-invoices/import', [PurchaseInvoicesController::class, 'importPurchaseInvoices']);
    Route::get('purchase-invoices/export', [PurchaseInvoicesController::class, 'exportPurchaseInvoices']);
    Route::resource('/purchase-invoices', PurchaseInvoicesController::class);
    Route::post('purchase-invoices/duplicate/{id}', [PurchaseInvoicesController::class, 'duplicate']);
    Route::put('purchase-invoices/status/{id}', [PurchaseInvoicesController::class, 'changeStatus']);
});


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

Route::middleware(['auth:api'])->group(function () {
    Route::post('assets/import', [AssetsController::class, 'importAssets']);
    Route::get('assets/export', [AssetsController::class, 'exportAssets']);
    Route::resource('/assets', AssetsController::class);
    Route::post('assets/duplicate/{id}', [AssetsController::class, 'duplicate']);
    Route::put('assets/status/{id}', [AssetsController::class, 'changeStatus']);
});


Route::middleware(['auth:api'])->group(function () {
    Route::post('custodies/import', [CustodiesController::class, 'importCustodies']);
    Route::get('custodies/export', [CustodiesController::class, 'exportCustodies']);
    Route::resource('/custodies', CustodiesController::class);
    Route::post('custodies/duplicate/{id}', [CustodiesController::class, 'duplicate']);
    Route::put('custodies/status/{id}', [CustodiesController::class, 'changeStatus']);
});


Route::middleware(['auth:api'])->group(function () {
    Route::post('bonuses/import', [BonusesController::class, 'importBonuses']);
    Route::get('bonuses/export', [BonusesController::class, 'exportBonuses']);
    Route::resource('/bonuses', BonusesController::class);
    Route::post('bonuses/duplicate/{id}', [BonusesController::class, 'duplicate']);
    Route::put('bonuses/status/{id}', [BonusesController::class, 'changeStatus']);
});


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
});


Route::middleware(['auth:api'])->group(function () {
    Route::resource('/taxes-expenses', TaxExpenseController::class);
});

Route::middleware(['auth:api'])->group(function () {
    Route::resource('/exchange-expenses', ExchangeExpenseController::class);
});

Route::middleware(['auth:api'])->group(function () {
    Route::resource('/insurance-expenses', InsuranceExpenseController::class);
});

Route::middleware(['auth:api'])->group(function () {

    //Exepese Section
    Route::resource('/expenses', ExpenseController::class);
    Route::post('/expenses/{id}/send', [ExpenseController::class, 'sendExpense']);

    //Employees Expenses
    Route::get('/employees-list', [EmployeesExpensesController::class, 'listEmployees']);

    Route::resource('/employees-expenses', EmployeesExpensesController::class);

    Route::get('/employee-expenses/{id}', [EmployeesExpensesController::class, 'employeeExpenses']);

    Route::put('/employee-expenses/{id}/accept', [EmployeesExpensesController::class, 'acceptExpense']);

    Route::put('/employee-expenses/{id}/reject', [EmployeesExpensesController::class, 'rejectExpense']);

    Route::put('/employee-expenses/edit-request', [EmployeesExpensesController::class, 'editRequest']);
    Route::post('/employee-expenses/count-by-status', [EmployeesExpensesController::class, 'countByStatus']);
});


Route::middleware(['auth:api'])->group(function () {
    Route::get('/expense-descussion/{id}', [ExpenseDiscussionController::class, 'messages']);
    Route::post('/expense-descussion/{id}/send', [ExpenseDiscussionController::class, 'sendMessage']);
});

Route::middleware(['auth:api'])->group(function () {
    Route::resource('/employee-timesheet', EmployeesTimesheetController::class);
    // Route::post('/expense-descussion/{id}/send', [ExpenseDiscussionController::class, 'sendMessage'] );
});


Route::get("test-exporting" , function (Request $request)
{
    $filde = \Illuminate\Http\UploadedFile::fake()->create("test");
    dd(File::basename($filde->getRealPath())  );

    $e = new ExportedFilesProcessor();
    $e->setExportedDataVersionName("signup-users" . time());

    $old = storage-path("app/public/testExporting");
//    $e->addFolderToExport($old)->addFileToExport();
    return $e->export();


    return File::name($old);
    $new = storage-path("app/public/testMoving");
    return File::copyDirectory($old , $new);

    $files = File::files($files[0]);
    $paths = [];
    foreach ($files as $file)
    {
        $paths[] = $file->isDir();
    }
    return $paths;

    $file = new \Illuminate\Http\UploadedFile(
        public-path("storage/testExporting/test.csv") , "test.csv"
    );
//    $file = $request->file("testFile");
    $filePath = $file->getRealPath();
    $fileUploader = new \App\CustomLibs\CustomFileSystem\S3CustomFileSystem\CustomFileUploader\S3CustomFileUploader();
    $fileNewPath =   "/testFolder/" . "test.csv";
    $fileUploader->makeFileReadyToStore($fileNewPath , $file);
    return $fileUploader->uploadFiles();

//    $file = app-path("../routes") . "/test.json";
//    response()->file($file);

//    return \Illuminate\Support\Facades\Response::Download($file , "test.json");

    $request->merge(["type" => "csv"]);
    $request->merge(["type" => "json"]);
//
//    $request->merge(["type" => "excel"]);
    return (new SignUpExporterBuilder($request))->build()->export();


//    $data = \Illuminate\Support\Facades\DB::table("users")->lazyById(100 , "id");
//    $data = \Illuminate\Support\Facades\DB::table("users")->cursor(100 , "id");
//    var-dump($data);
//    $data = ["Sign Up Users" => collect(["name" => "Muhammed"  , "id" => 2 , "phoneNumber" => "05375453731" ])];
//        $data = ["name" => "Muhammed"  , "id" => 2 , "phoneNumber" => "05375453731" ];
    $data = QueryBuilder::for(User::class)
        ->allowedIncludes(['profile' , 'profile.country','role'])
        ->datesFiltering()->customOrdering()->lazyById();
    $data  = collect(  $data->toArray() );

    return (new FastExcel($data))->download("test.csv");

//    File::move($fileExporting , public-path("storage/testExporting/test.csv"));
//    return File::exists($fileExporting);
//    return File::get($fileExporting);
//    \Illuminate\Support\Facades\Storage::move($fileExporting , "")
//    \Illuminate\Support\Facades\File::move($fileExporting , )
});