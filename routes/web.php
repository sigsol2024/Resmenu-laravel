<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Public\CheckoutController;
use App\Http\Controllers\Public\HealthController;
use App\Http\Controllers\Public\MenuController;
use App\Http\Controllers\Public\OrderConfirmationController;
use App\Http\Controllers\Public\QrRedirectController;
use App\Http\Controllers\Public\QrTemplatePreviewController;
use App\Http\Controllers\Public\ReservationController;
use App\Http\Controllers\Public\TemplatePreviewController;
use Illuminate\Support\Facades\Route;

Route::get('/health', HealthController::class)->name('health');

Route::get('/', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:10,1')->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('throttle:30,1')->group(function () {
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register/otp', [RegisterController::class, 'sendOtp'])->middleware('throttle:5,1')->name('register.otp');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.submit');
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgot'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendReset'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

Route::middleware('throttle:60,1')->group(function () {
    Route::get('/restaurant/{slug}/checkout', [CheckoutController::class, 'show'])->name('public.checkout');
    Route::match(['get', 'post'], '/restaurant/{slug}/checkout/submit', [CheckoutController::class, 'submit'])->name('public.checkout.submit');
    Route::match(['get', 'post'], '/restaurant/{slug}/reservation', [ReservationController::class, 'show'])->name('public.reservation');
});
Route::get('/orders/{order}/confirmation', [OrderConfirmationController::class, 'show'])->name('public.order.confirmation');
Route::get('/order-payment/callback/{gateway}', \App\Http\Controllers\Public\OrderPaymentCallbackController::class)
    ->where('gateway', 'paystack|flutterwave')
    ->name('public.order.payment-callback');

Route::get('/restaurant/{slug}', [MenuController::class, 'show'])->name('public.menu');
Route::get('/restaurant/{slug}/{section}', [MenuController::class, 'show'])
    ->name('public.menu.section')
    ->where('section', '[a-z0-9-]+');

Route::get('/qr/{slug}', QrRedirectController::class)->name('public.qr');
Route::get('/qr/{slug}/{section}', QrRedirectController::class)->name('public.qr.section')
    ->where('section', '[a-z0-9-]+');

Route::get('/api/qr-template-preview-image', QrTemplatePreviewController::class)
    ->middleware('throttle:120,1')
    ->name('public.qr.template-preview');

Route::get('/templates/{template}/preview', [TemplatePreviewController::class, 'show'])
    ->where('template', '[0-9]+')
    ->name('public.template.preview');

Route::get('/faq', [\App\Http\Controllers\Public\MarketingController::class, 'faq'])->name('public.faq');
Route::get('/contact', [\App\Http\Controllers\Public\MarketingController::class, 'contact'])->name('public.contact');
Route::get('/terms', [\App\Http\Controllers\Public\MarketingController::class, 'terms'])->name('public.terms');
Route::get('/restaurants-list', [\App\Http\Controllers\Public\MarketingController::class, 'restaurantsList'])->name('public.restaurants-list');
Route::get('/templates', [\App\Http\Controllers\Public\MarketingController::class, 'templates'])->name('public.templates');
Route::get('/bank-transfer-pending', [\App\Http\Controllers\Public\BankTransferPendingController::class, 'show'])->name('public.bank-transfer-pending');
Route::get('/payment-failed', [\App\Http\Controllers\Public\PaymentFailedController::class, 'show'])->name('public.payment-failed');
Route::get('/reservations/{reservation}/confirmation', [\App\Http\Controllers\Public\ReservationConfirmationController::class, 'show'])
    ->name('public.reservation.confirmation');

require __DIR__.'/manager.php';
require __DIR__.'/admin.php';
