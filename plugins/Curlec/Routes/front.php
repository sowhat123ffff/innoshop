<?php

use Illuminate\Support\Facades\Route;

use Plugin\Curlec\Controllers\PaymentController;


Route::middleware('web')->group(function () {
    // Locale-prefixed group for multi-locale setups
    Route::group(['prefix' => '{locale}', 'where' => ['locale' => '[a-zA-Z0-9_-]+']], function () {
        Route::post('/payment/curlec/order', [PaymentController::class, 'createOrder'])->name('curlec.payment.order');
    });
    // Non-prefixed route for single-locale setups
    Route::post('/payment/curlec/order', [PaymentController::class, 'createOrder'])->name('curlec.payment.order');
});