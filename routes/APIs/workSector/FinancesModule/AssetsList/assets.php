<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkSector\FinancesModule\AssetsList\AssetsController;

Route::middleware(['auth:api'])->group(function () {
    Route::post('assets/import', [AssetsController::class, 'importAssets']);
    Route::get('assets/export', [AssetsController::class, 'exportAssets']);
    Route::resource('/assets', AssetsController::class);
    Route::post('assets/duplicate/{id}', [AssetsController::class, 'duplicate']);
    Route::put('assets/status/{id}', [AssetsController::class, 'changeStatus']);
});
