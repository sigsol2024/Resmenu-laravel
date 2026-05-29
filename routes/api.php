<?php

use App\Http\Controllers\Api\BankTransferApiController;
use App\Http\Controllers\Api\MenuApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\QrApiController;
use App\Http\Controllers\Api\ReservationApiController;
use App\Http\Controllers\Api\RestaurantApiController;
use App\Http\Controllers\Api\SubscriptionPlanApiController;
use App\Http\Controllers\Api\TemplateApiController;
use App\Http\Controllers\Api\WebhookController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:120,1')->group(function () {
    Route::get('/subscription-plans', [SubscriptionPlanApiController::class, 'index']);
    Route::get('/restaurants', [RestaurantApiController::class, 'index']);
    Route::get('/templates', [TemplateApiController::class, 'index']);
    Route::get('/restaurants/{slug}/menu', [MenuApiController::class, 'show']);
});

Route::middleware('throttle:30,1')->prefix('bank-transfer')->group(function () {
    Route::post('/confirm', [BankTransferApiController::class, 'confirm']);
    Route::post('/expire', [BankTransferApiController::class, 'expire']);
    Route::post('/cancel-order', [BankTransferApiController::class, 'cancelOrder']);
});

Route::middleware('throttle:60,1')->prefix('orders')->group(function () {
    Route::post('/', [OrderApiController::class, 'store']);
    Route::post('/{order}/cancel', [OrderApiController::class, 'cancel']);
    Route::get('/{order}', [OrderApiController::class, 'show'])->middleware('auth:manager');
    Route::patch('/{order}/status', [OrderApiController::class, 'updateStatus'])->middleware(['auth:manager', 'manager.tenant']);
});

Route::middleware('throttle:60,1')->prefix('reservations')->group(function () {
    Route::get('/slots', [ReservationApiController::class, 'slots']);
    Route::get('/availability', [ReservationApiController::class, 'availability']);
    Route::post('/', [ReservationApiController::class, 'store']);
    Route::get('/{reservation}', [ReservationApiController::class, 'show'])->middleware('auth:manager');
    Route::patch('/{reservation}/status', [ReservationApiController::class, 'updateStatus'])->middleware(['auth:manager', 'manager.tenant']);
});

Route::middleware(['auth:manager', 'manager.tenant', 'session.idle:manager'])->group(function () {
    Route::get('/orders/analytics', [OrderApiController::class, 'analytics']);
    Route::get('/reservations/analytics', [ReservationApiController::class, 'analytics']);
    Route::post('/reservations/deposit', [ReservationApiController::class, 'updateDeposit']);
    Route::match(['get', 'post'], '/table-inventory', [ReservationApiController::class, 'tableInventory']);
});

Route::prefix('qr')->middleware('throttle:60,1')->group(function () {
    Route::post('/generate', [QrApiController::class, 'generate'])->middleware(['auth:manager', 'manager.tenant']);
    Route::get('/export', [QrApiController::class, 'export'])->middleware(['auth:manager', 'manager.tenant']);
});

Route::prefix('webhooks')->middleware('throttle:120,1')->group(function () {
    Route::post('/paystack', [WebhookController::class, 'paystack']);
    Route::post('/flutterwave', [WebhookController::class, 'flutterwave']);
    Route::post('/restaurant/paystack', [WebhookController::class, 'restaurantPaystack']);
    Route::post('/restaurant/flutterwave', [WebhookController::class, 'restaurantFlutterwave']);
    Route::match(['get', 'post'], '/email-suppression', [WebhookController::class, 'emailSuppression']);
});
