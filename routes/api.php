<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductsController;
use App\Http\Middleware\IsAdminAuth;
use App\Http\Middleware\IsUserAuth;
use Illuminate\Support\Facades\Route;

// RUTAS PUBLICAS
Route::post('register', [AuthController::class, "register"]);
Route::post('login', [AuthController::class, "login"]);

// RUTAS PRIVADAS
Route::middleware([IsUserAuth::class])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout');
        Route::post('me', 'me');
    });

    Route::get("products", [ProductsController::class, "getProducts"]);

    Route::middleware([IsAdminAuth::class])->group(function () {
        Route::controller(ProductsController::class)->group(function () {
            Route::post('products', 'addProduct');
            Route::get('/products/{id}', 'getProductById');
            Route::patch('/products/{id}', 'updateProductById');
            Route::delete('/products/{id}', 'delteProductById');
        });
    });
});
