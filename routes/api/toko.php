<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Toko\TokoController;

Route::middleware(['auth:sanctum', 'session.timeout'])->group(function () {
    Route::controller(TokoController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/submit', 'submit');
        Route::get('/detail', 'show');
        Route::post('/update', 'update');
        Route::post('/delete', 'destroy');
    });
});