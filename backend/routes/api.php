<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

// javne rute (samo čitanje)
Route::apiResource('products', ProductController::class)->only(['index', 'show']);
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// rute za prijavljene korisnike
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::post('/checkout', [CheckoutController::class, 'store']);

    // rute samo za admin i super admin
    Route::middleware([AdminMiddleware::class])->group(function () {
        // upravljanje korisnicima
        Route::apiResource('users', UserController::class);

        // dodavanje, mijenjanje i brisanje proizvoda i kategorija
        Route::apiResource('products', ProductController::class)->except(['index', 'show']);
        Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);

        Route::get('/orders', [OrderController::class, 'index']); // lista kupnji
        Route::get('/orders/{order}', [OrderController::class, 'show']);
        Route::get('/customers', [UserController::class, 'customers']); // lista kupaca
        Route::get('/stats/best-selling', [OrderController::class, 'bestSelling']); // najprodavaniji proizvodi
        Route::get('/stats/top-customers', [OrderController::class, 'topCustomers']); // top kupci

        Route::post('/admins', [UserController::class, 'createAdmin']); // samo superadmin
    });
});
