<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonalSector\PersonalTransactions\Inflow\{BonusesController, CustodiesController};

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
