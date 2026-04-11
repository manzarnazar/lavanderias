<?php

use App\Http\Controllers\API\Master\MasterController;
use App\Http\Controllers\API\Master\SellerMaster;
use App\Http\Controllers\API\Master\SellerMasterController;
use App\Http\Controllers\Seller\AuthController;
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\OrderController;
use App\Http\Controllers\Seller\RiderController;
use App\Http\Controllers\Seller\UserController as SellerUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PaymentGatewayController;
use App\Http\Controllers\Seller\PosController;
use App\Http\Controllers\Web\Products\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Auth Routes
Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register')->name('seller.register');
    Route::post('/send-otp', 'sendOTP');
    Route::post('/verify-otp', 'verifyOtp');
});

Route::middleware(['auth:api', 'role:store'])->group(function () {

    // Dashboard Routes
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index');
        Route::get('/orders-history', 'orderHistory');
    });

    // Profile Routes
    Route::controller(SellerUserController::class)->group(function () {
        Route::get('/profile', 'show');
        Route::post('/update', 'update');
        Route::post('/profile-update', 'profileUpdate');
    });

    // order Routes
    Route::controller(OrderController::class)->group(function () {
        Route::get('/orders', 'index');
        Route::post('/orders-cancelled', 'cancel');
        Route::post('/orders/status-update', 'statusUpdate');
        Route::get('/orders/details', 'show');
        Route::get('/status-wise-orders', 'statusWiseOrders');
    });

    // Rider Routes
    Route::controller(RiderController::class)->group(function () {
        Route::get('/riders', 'index');
        Route::post('/riders/create', 'store');
        Route::get('/riders/{driver}/show', 'show');
        Route::post('/riders/{driver}/update', 'update')->name('riders.update');
        Route::post('/riders-assign-order', 'assignRider');
    });


    Route::controller(PosController::class)->group(function(){
        Route::post('/pos', 'posStore');
        Route::get('/pos/customer','posCustomer');
        Route::get('/pos/service','posService');
        Route::get('/fetch/variants', 'fetchVariants')->name('pos.fetch.variants');
        Route::get('/fetch/products', 'fetchProducts')->name('pos.fetch.products');
    });
    Route::controller(SellerMasterController::class)->group(function () {
        Route::get('/master', 'index');

    });
    Route::controller(PaymentGatewayController::class)->group(function () {
        Route::post('payment/process/orders/{order}', 'processOrder')->name('payment.process.orders');
    });
});
