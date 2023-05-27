<?php

use App\Http\Controllers\SystemAdminPanel\Auth\AdminAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkSector\UsersModule\{
    AuthController,
    UserController,
};

Route::controller(AdminAuthController::class)->prefix('admin')->group(function () {
    Route::post('logout', 'logout');
    Route::post('register', 'register');
    Route::post('login', 'login');
});
