<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\SelectOptionsController;

// use App\Http\Middleware\UpdateLifetime;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(['auth:sanctum', 'session.timeout']);

// Authentication Endpoint
Route::controller(AuthenticationController::class)->group(function () {
    Route::any('/no-auth', 'noAuth')->name('noAuth');
    
    Route::post('register', 'register')->name('register');
    Route::post('login', 'login')->name('login');
    Route::post('account-activation', 'accountActivation')->name('accountActivation');

    Route::prefix('forgot-password')->name('forgot-password.')->group(function () {
        Route::post('check-email', 'checkMailForgotPassword')->name('checkMailForgotPassword');
        Route::post('check-token', 'checkTokenForgotPassword')->name('checkTokenForgotPassword');
        Route::post('update-password', 'UpdatePassword')->name('UpdatePassword');
    });

    Route::middleware(['auth:sanctum', 'session.timeout'])->group(function () {
        Route::get('/user-info', 'getUserInfo')->name('getUserInfo');
        Route::get('/revoke', 'revokeToken')->name('revokeToken');
        Route::post('/check-auth', 'checkAuth')->name('checkAuth');
        Route::post('/switch-role', 'switchRole')->name('switchRole');
    });
});

// Select Options Endpoint
Route::controller(SelectOptionsController::class)->prefix('options')->group(function(){
    Route::get('provinsi', 'provinsi');
    Route::get('kota', 'kota');
    Route::get('kecamatan', 'kecamatan');
    Route::get('kelurahan', 'kelurahan');
    Route::get('products', 'products');
    Route::get('categories', 'categories');
});

// Toko Endpoint
Route::prefix('toko')->name('toko.')->group(__DIR__ . '/api/toko.php');

// Product Endpoint
Route::prefix('products')->name('products.')->group(__DIR__ . '/api/product.php');

// Categories Endpoint
Route::prefix('categories')->name('categories.')->group(__DIR__ . '/api/categories.php');

// Carts Endpoint
Route::prefix('carts')->name('carts.')->group(__DIR__ . '/api/carts.php');

// Orders Endpoint
Route::prefix('orders')->name('orders.')->group(__DIR__ . '/api/orders.php');

// Review Endpoint
Route::prefix('reviews')->name('reviews.')->group(__DIR__ . '/api/reviews.php');