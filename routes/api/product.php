<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;

Route::controller(ProductController::class)->group(function () {
    Route::get('/', 'index');
});

Route::middleware(['auth:sanctum', 'session.timeout'])->group(function () {
    Route::controller(ProductController::class)->group(function () {
        Route::post('/store', 'store');
        Route::get('/detail/{id}', 'show');
        Route::post('/update/{id}', 'update');
        Route::post('/delete/{id}', 'destroy');
    });
});