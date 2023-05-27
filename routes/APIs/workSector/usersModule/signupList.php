<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkSector\UsersModule\UserController;
use App\Http\Controllers\WorkSector\UsersModule\SignUpController;

Route::middleware(['auth:api'])->group(function () {
    Route::resource('signup-list', SignUpController::class)->except("update");
    Route::put('signup-list/update/{user}', [SignUpController::class, 'update']);
    Route::delete('signup-list/{user}', [UserController::class, 'destroy']);
    Route::put('signup-list/status/{user}', [SignUpController::class, 'changeAccountStatus']);
});
