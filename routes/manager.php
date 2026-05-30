<?php

use App\Http\Controllers\Manager\BillingController;
use App\Http\Controllers\Manager\CategoryController;
use App\Http\Controllers\Manager\CustomizationController;
use App\Http\Controllers\Manager\DashboardController;
use App\Http\Controllers\Manager\MenuItemController;
use App\Http\Controllers\Manager\OrderController;
use App\Http\Controllers\Manager\ProfileController;
use App\Http\Controllers\Manager\QrController;
use App\Http\Controllers\Manager\ReservationController;
use App\Http\Controllers\Manager\SectionController;
use App\Http\Controllers\Manager\SettingsController;
use App\Http\Controllers\Manager\SlugDashboardRedirectController;
use App\Http\Controllers\Public\QrImageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:manager', 'manager.tenant', 'session.idle:manager'])
    ->prefix('manager')
    ->name('manager.')
    ->group(function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');

        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('menu-items', MenuItemController::class)->except(['show']);
        Route::resource('sections', SectionController::class)->except(['show']);

        Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

        Route::match(['get', 'post'], '/customization', [CustomizationController::class, 'index'])->name('customization');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/all', [OrderController::class, 'list'])->name('orders.list');
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');

        Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
        Route::post('/reservations/deposit', [ReservationController::class, 'updateDeposit'])->name('reservations.deposit');
        Route::get('/reservations/all', [ReservationController::class, 'list'])->name('reservations.list');
        Route::patch('/reservations/{reservation}/status', [ReservationController::class, 'updateStatus'])->name('reservations.status');
        Route::get('/table-inventory', [\App\Http\Controllers\Manager\TableInventoryController::class, 'index'])->name('table-inventory.index');

        Route::match(['get', 'post'], '/billing', [BillingController::class, 'index'])->name('billing.index');
        Route::match(['get', 'post'], '/billing/checkout', [BillingController::class, 'checkout'])->name('billing.checkout');
        Route::post('/billing/process-payment', [BillingController::class, 'processPayment'])->name('billing.process-payment');
        Route::get('/billing/payment-callback', [BillingController::class, 'paymentCallback'])->name('billing.payment-callback');
        Route::get('/billing/transactions', [BillingController::class, 'transactionHistory'])->name('billing.transactions');
        Route::get('/payment-settings', [BillingController::class, 'paymentSettings'])->name('billing.payment-settings');
        Route::post('/payment-settings', [BillingController::class, 'savePaymentSettings'])->name('billing.payment-settings.save');

        Route::match(['get', 'post'], '/qr', [QrController::class, 'code'])->name('qr.code');
        Route::get('/qr/image', QrImageController::class)->name('qr.image');
        Route::get('/qr/analytics', [QrController::class, 'analytics'])->name('qr.analytics');
        Route::get('/qr/analytics/export', [QrController::class, 'exportCsv'])->name('qr.analytics.export');

        Route::get('/{slug}', SlugDashboardRedirectController::class)
            ->where('slug', '[a-z0-9-]+')
            ->name('dashboard.slug');
    });
