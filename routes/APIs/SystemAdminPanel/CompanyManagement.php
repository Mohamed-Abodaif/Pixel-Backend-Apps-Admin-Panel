<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SystemAdminPanel\Company\CompanyManagementController;

Route::controller(CompanyManagementController::class)->prefix('company')->group(function () {
    Route::get('signup-list', 'signupList');
    Route::get('list', 'companyList');
    Route::get('hidden-list', 'companyHiddenList');
    Route::delete('hide/{company}', 'hide');
    Route::delete('delete/{company}', 'delete');
    Route::put('update-register-status', 'updateRegisterStatus');
    Route::put('update', 'updateCompany');
    Route::put('update-list-status', 'updateCompanyListStatus');
});