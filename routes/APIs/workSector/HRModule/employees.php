<?php

use App\Http\Controllers\PersonalSector\PersonalTransactions\OutFlow\ExpenseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkSector\HRModule\EmployeesExpensesController;

Route::middleware(['auth:api'])->group(function () {

    Route::get('/employees-list', [EmployeesExpensesController::class, 'listEmployees']);

    Route::resource('/employees-expenses', EmployeesExpensesController::class);

    Route::get('/employee-expenses/{id}', [EmployeesExpensesController::class, 'employeeExpenses']);

    Route::put('/employee-expenses/{id}/accept', [EmployeesExpensesController::class, 'acceptExpense']);

    Route::put('/employee-expenses/{id}/reject', [EmployeesExpensesController::class, 'rejectExpense']);

    Route::put('/employee-expenses/edit-request', [EmployeesExpensesController::class, 'editRequest']);

    Route::post('/employee-expenses/count-by-status', [EmployeesExpensesController::class, 'countByStatus']);

    Route::resource('/expenses', ExpenseController::class);

    Route::post('/expenses/{id}/send', [ExpenseController::class, 'sendExpense']);
});
