<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\OrderController;




Route::apiResource('users', UserController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('categories',CategoryController::class);

//javne rute
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//rute za logiranje korisnika
Route::middleware('auth:sanctum')->group(function (){
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user',[AuthController::class, 'getUser']);
    Route::post('/checkout', [CheckoutController::class, 'store']);

   // rute samo za admin i super admin
    Route::middleware([AdminMiddleware::class])->group(function () {

        // Admin može upravljati korisnicima
        Route::apiResource('users', UserController::class);

        // Admin može DODAVATI, BRISATI i MIJENJATI proizvode/kategorije
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

        Route::post('/categories', [CategoryController::class, 'store']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

       Route::get('/orders', [OrderController::class, 'index']);//lista kupnji
       Route::get('/orders/{order}', [OrderController::class, 'show']);
       Route::get('/customers', [UserController::class, 'customers']); //lista kupaca
       Route::get('/stats/best-selling', [OrderController::class, 'bestSelling']); //najprodavaniji proizvodi
       Route::get('/stats/top-customers', [OrderController::class, 'topCustomers']); // top kupci


       Route::post('/admins', [UserController::class, 'createAdmin']);//superadmin


    });
});
