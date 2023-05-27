<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkSector\CompanyModule\CompaniesController;
use App\Http\Controllers\WorkSector\CompanyModule\CompanyFileController;


Route::controller(CompanyFileController::class)->prefix('company')->group(function () {
    Route::get('export', 'exportCompanies');
    Route::get('import', 'importCompanies');
});
