<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PaymentSettingsController;
use App\Http\Controllers\Admin\QrTemplateController;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\RestaurantHubController;
use App\Http\Controllers\Public\QrImageController;
use App\Http\Controllers\Admin\RestaurantSearchController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\SubscriptionPlanController;
use App\Http\Controllers\Admin\TemplateController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:10,1')->name('login.submit');

    Route::middleware(['auth:admin', 'session.idle:admin'])->group(function () {
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
        Route::get('/dashboard', DashboardController::class)->name('dashboard');

        Route::get('/restaurants', [RestaurantController::class, 'index'])->name('restaurants.index');
        Route::get('/restaurants/create', [RestaurantController::class, 'create'])->name('restaurants.create');
        Route::post('/restaurants', [RestaurantController::class, 'store'])->name('restaurants.store');
        Route::get('/restaurants/search', [RestaurantSearchController::class, 'index'])->name('restaurants.search');
        Route::get('/restaurants/{restaurant}', [RestaurantController::class, 'show'])->name('restaurants.show');
        Route::match(['get', 'post'], '/restaurants/{restaurant}/hub', [RestaurantHubController::class, 'show'])->name('restaurants.hub');
        Route::get('/qr-image', QrImageController::class)->name('qr.image');
        Route::get('/restaurants/{restaurant}/edit', [RestaurantController::class, 'edit'])->name('restaurants.edit');
        Route::put('/restaurants/{restaurant}', [RestaurantController::class, 'update'])->name('restaurants.update');
        Route::delete('/restaurants/{restaurant}', [RestaurantController::class, 'destroy'])->name('restaurants.destroy');

        Route::get('/subscription-plans', [SubscriptionPlanController::class, 'index'])->name('subscription-plans.index');
        Route::get('/subscription-plans/create', [SubscriptionPlanController::class, 'create'])->name('subscription-plans.create');
        Route::post('/subscription-plans', [SubscriptionPlanController::class, 'store'])->name('subscription-plans.store');
        Route::get('/subscription-plans/{subscriptionPlan}/edit', [SubscriptionPlanController::class, 'edit'])->name('subscription-plans.edit');
        Route::put('/subscription-plans/{subscriptionPlan}', [SubscriptionPlanController::class, 'update'])->name('subscription-plans.update');
        Route::delete('/subscription-plans/{subscriptionPlan}', [SubscriptionPlanController::class, 'destroy'])->name('subscription-plans.destroy');
        Route::post('/subscription-plans/{subscriptionPlan}/toggle', [SubscriptionPlanController::class, 'toggle'])->name('subscription-plans.toggle');

        Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::patch('/subscriptions/{subscription}', [SubscriptionController::class, 'update'])->name('subscriptions.update');

        Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
        Route::get('/templates/{template}/edit', [TemplateController::class, 'edit'])->name('templates.edit');
        Route::put('/templates/{template}', [TemplateController::class, 'update'])->name('templates.update');
        Route::post('/templates/{template}/toggle', [TemplateController::class, 'toggle'])->name('templates.toggle');
        Route::get('/qr-templates', [QrTemplateController::class, 'index'])->name('qr-templates.index');
        Route::get('/qr-templates/create', [QrTemplateController::class, 'create'])->name('qr-templates.create');
        Route::post('/qr-templates', [QrTemplateController::class, 'store'])->name('qr-templates.store');
        Route::get('/qr-templates/{qrTemplate}/edit', [QrTemplateController::class, 'edit'])->name('qr-templates.edit');
        Route::put('/qr-templates/{qrTemplate}', [QrTemplateController::class, 'update'])->name('qr-templates.update');
        Route::delete('/qr-templates/{qrTemplate}', [QrTemplateController::class, 'destroy'])->name('qr-templates.destroy');
        Route::post('/qr-templates/regenerate-previews', [QrTemplateController::class, 'regeneratePreviews'])->name('qr-templates.regenerate-previews');

        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
        Route::get('/payment-settings', [PaymentSettingsController::class, 'index'])->name('payment-settings.index');
        Route::post('/payment-settings', [PaymentSettingsController::class, 'update'])->name('payment-settings.update');
        Route::put('/payment-settings', [PaymentSettingsController::class, 'update'])->name('payment-settings.update.put');

        Route::match(['get', 'post'], '/settings', [SettingsController::class, 'index'])->name('settings.index');
    });
});
