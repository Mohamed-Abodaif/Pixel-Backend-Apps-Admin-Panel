<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkSector\SystemConfigurationControllers\RolesAndPermissions\RolesController;



// Route::resource('roles', RolesController::class);
// Route::get('roles/all', [RolesController::class,'list']);
Route::middleware(['auth:api'])->group(function () {
    Route::group(['prefix' => 'list'], function () {
        Route::get('roles', [RolesController::class, 'list']);
    });

    Route::put('roles/{role}/status', [RolesController::class, 'switchRole']);
    Route::get('all-permissions', [RolesController::class, 'allPermission']);
    Route::resource('roles', RolesController::class)->except(['edit']);
});
