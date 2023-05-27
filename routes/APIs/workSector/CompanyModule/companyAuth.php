<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkSector\CompanyModule\CompanyAuthController;

Route::controller(CompanyAuthController::class)->prefix('company')->group(function () {
    Route::post('login', 'login')->middleware('reqLimit');
    Route::post('register', 'register');
    Route::post('forget-id', 'forgetId')->middleware('reqLimit');
    Route::post('verify-email', 'verifyEmail');
    Route::post('check/status', 'checkStatus')->middleware('reqLimit');
});