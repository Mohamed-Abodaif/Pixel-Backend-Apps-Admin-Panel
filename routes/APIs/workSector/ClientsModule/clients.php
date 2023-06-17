<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkSector\ClientsModule\ClientsController;

// Route::->group(function () {
Route::resource('clients', ClientsController::class)->parameters(["clients" => "client"]);

// Route::post('/clients/import', [ClientsController::class, 'importClients']);
// Route::get('/clients/export', [ClientsController::class, 'exportClients']);
// Route::post('/clients/duplicate/{id}', [ClientsController::class, 'duplicate']);
// Route::put('/clients/status/{id}', [ClientsController::class, 'changeStatus']);
// });
