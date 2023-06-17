<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkSector\UsersModule\{UserController, EmployeeController};

// Route::middleware(['auth:api'])->group(function () {
    Route::resource('employees', EmployeeController::class)->except("destroy", "update");
    Route::put('employees/update/{user}', [EmployeeController::class, 'update']);
    Route::delete('employees/{user}', [UserController::class, 'destroy']);
    Route::put('employees/status/{user}', [EmployeeController::class, 'changeAccountStatus']);
// });
