<?php

use App\Http\Controllers\Manager\BillingController;
use App\Http\Controllers\Manager\CategoryController;
use App\Http\Controllers\Manager\CustomizationController;
use App\Http\Controllers\Manager\DashboardController;
use App\Http\Controllers\Manager\MenuImportController;
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

Route::middleware(['auth:manager', 'manager.tenant', 'subscription.active', 'session.idle:manager'])
    ->prefix('manager')
    ->name('manager.')
    ->group(function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');

        Route::post('/email/verification-notification', [\App\Http\Controllers\Auth\ManagerEmailVerificationController::class, 'resend'])
            ->middleware('throttle:6,1')
            ->name('verification.resend');

        Route::middleware('manager.email.verified')->group(function () {
            Route::resource('categories', CategoryController::class)->except(['show', 'index']);
            Route::resource('menu-items', MenuItemController::class)->except(['show', 'index']);
            Route::resource('sections', SectionController::class)->except(['show', 'index']);
            // Presentation mutations only — GET customization stays open below.
            Route::post('/customization', [CustomizationController::class, 'index'])->name('customization.save');

            // The third throttle argument is a key prefix; without it every throttle a manager hits shares one counter.
            Route::middleware('throttle:30,1,menu-import')->group(function () {
                Route::get('menu-import/template', [MenuImportController::class, 'template'])->name('menu-import.template');
                Route::get('menu-import/sample', [MenuImportController::class, 'sample'])->name('menu-import.sample');
                Route::post('menu-import', [MenuImportController::class, 'upload'])->name('menu-import.upload');
                Route::get('menu-import/{token}', [MenuImportController::class, 'preview'])->where('token', '[A-Za-z0-9]{40}')->name('menu-import.preview');
                Route::post('menu-import/{token}/import', [MenuImportController::class, 'import'])->where('token', '[A-Za-z0-9]{40}')->name('menu-import.import');
                Route::delete('menu-import/{token}', [MenuImportController::class, 'cancel'])->where('token', '[A-Za-z0-9]{40}')->name('menu-import.cancel');
                Route::get('menu-import-result', [MenuImportController::class, 'result'])->name('menu-import.result');
            });
            // Every preview edit is re-analysed on the server (debounced), so this needs more headroom.
            Route::post('menu-import/{token}/analyze', [MenuImportController::class, 'analyze'])
                ->where('token', '[A-Za-z0-9]{40}')
                ->middleware('throttle:120,1,menu-import-analyze')
                ->name('menu-import.analyze');
        });

        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('menu-items', [MenuItemController::class, 'index'])->name('menu-items.index');
        Route::get('sections', [SectionController::class, 'index'])->name('sections.index');

        Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

        Route::get('/customization', [CustomizationController::class, 'index'])->name('customization');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/all', [OrderController::class, 'list'])->name('orders.list');
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');

        Route::get('/bank-transfers', [\App\Http\Controllers\Manager\BankTransferController::class, 'index'])->name('bank-transfers.index');
        Route::post('/bank-transfers/{draft}/approve', [\App\Http\Controllers\Manager\BankTransferController::class, 'approve'])->middleware('throttle:20,1')->name('bank-transfers.approve');
        Route::post('/bank-transfers/{draft}/reject', [\App\Http\Controllers\Manager\BankTransferController::class, 'reject'])->middleware('throttle:20,1')->name('bank-transfers.reject');

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
