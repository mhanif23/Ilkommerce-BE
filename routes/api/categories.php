<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Category\CategoryController;

Route::middleware(['auth:sanctum', 'session.timeout'])->group(function () {
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/store', 'store');
        Route::get('/detail/{id}', 'show');
        Route::post('/update/{id}', 'update');
        Route::post('/delete/{id}', 'destroy');
    });
});