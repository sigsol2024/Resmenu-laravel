<?php

use App\Http\Controllers\Admin\AdminsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ImpersonationController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PaymentSettingsController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\QrTemplateController;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Public\QrImageController;
use App\Http\Controllers\Admin\RestaurantSearchController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\SubscriptionPlanController;
use App\Http\Controllers\Admin\TemplateController;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:10,1')->name('login.submit');

    Route::middleware(['auth:admin', 'admin.active', 'session.idle:admin'])->group(function () {
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
        Route::get('/dashboard', DashboardController::class)->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::match(['put', 'post'], '/profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::get('/restaurants', [RestaurantController::class, 'index'])->name('restaurants.index');
        Route::get('/restaurants/create', [RestaurantController::class, 'create'])->name('restaurants.create');
        Route::post('/restaurants', [RestaurantController::class, 'store'])->name('restaurants.store');
        Route::get('/restaurants/search', [RestaurantSearchController::class, 'index'])->name('restaurants.search');
        Route::get('/restaurants/{restaurant}', [RestaurantController::class, 'show'])->name('restaurants.show');
        Route::post('/restaurants/{restaurant}/impersonate', [ImpersonationController::class, 'start'])->name('restaurants.impersonate');
        // Temporary bookmark-safe redirect (hub CRUD removed — use Login as Manager).
        Route::match(['get', 'post'], '/restaurants/{restaurant}/hub', function (Restaurant $restaurant) {
            return redirect()
                ->route('admin.restaurants.index')
                ->with('success', 'Use Login as Manager to open the restaurant.');
        })->name('restaurants.hub');
        Route::get('/qr-image', QrImageController::class)->name('qr.image');
        Route::get('/restaurants/{restaurant}/edit', [RestaurantController::class, 'edit'])->name('restaurants.edit');
        Route::put('/restaurants/{restaurant}', [RestaurantController::class, 'update'])->name('restaurants.update');
        Route::post('/restaurants/{restaurant}/suspend', [RestaurantController::class, 'suspend'])->name('restaurants.suspend');
        Route::post('/restaurants/{restaurant}/restore', [RestaurantController::class, 'restore'])->name('restaurants.restore');
        Route::delete('/restaurants/{restaurant}', [RestaurantController::class, 'destroy'])->name('restaurants.destroy');

        Route::middleware('admin.permission:subscription_plans')->group(function () {
            Route::get('/subscription-plans', [SubscriptionPlanController::class, 'index'])->name('subscription-plans.index');
            Route::get('/subscription-plans/create', [SubscriptionPlanController::class, 'create'])->name('subscription-plans.create');
            Route::post('/subscription-plans', [SubscriptionPlanController::class, 'store'])->name('subscription-plans.store');
            Route::get('/subscription-plans/{subscriptionPlan}/edit', [SubscriptionPlanController::class, 'edit'])->name('subscription-plans.edit');
            Route::put('/subscription-plans/{subscriptionPlan}', [SubscriptionPlanController::class, 'update'])->name('subscription-plans.update');
            Route::delete('/subscription-plans/{subscriptionPlan}', [SubscriptionPlanController::class, 'destroy'])->name('subscription-plans.destroy');
            Route::post('/subscription-plans/{subscriptionPlan}/toggle', [SubscriptionPlanController::class, 'toggle'])->name('subscription-plans.toggle');
        });

        Route::middleware('admin.permission:subscriptions')->group(function () {
            Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
            Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');
            Route::patch('/subscriptions/{subscription}', [SubscriptionController::class, 'update'])->name('subscriptions.update');
        });

        Route::middleware('admin.permission:templates')->group(function () {
            Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
            Route::get('/templates/{template}/edit', [TemplateController::class, 'edit'])->name('templates.edit');
            Route::put('/templates/{template}', [TemplateController::class, 'update'])->name('templates.update');
            Route::post('/templates/{template}/toggle', [TemplateController::class, 'toggle'])->name('templates.toggle');
        });

        Route::middleware('admin.permission:qr_templates')->group(function () {
            Route::get('/qr-templates', [QrTemplateController::class, 'index'])->name('qr-templates.index');
            Route::get('/qr-templates/create', [QrTemplateController::class, 'create'])->name('qr-templates.create');
            Route::post('/qr-templates', [QrTemplateController::class, 'store'])->name('qr-templates.store');
            Route::get('/qr-templates/{qrTemplate}/edit', [QrTemplateController::class, 'edit'])->name('qr-templates.edit');
            Route::put('/qr-templates/{qrTemplate}', [QrTemplateController::class, 'update'])->name('qr-templates.update');
            Route::delete('/qr-templates/{qrTemplate}', [QrTemplateController::class, 'destroy'])->name('qr-templates.destroy');
            Route::post('/qr-templates/regenerate-previews', [QrTemplateController::class, 'regeneratePreviews'])->name('qr-templates.regenerate-previews');
        });

        Route::middleware('admin.permission:payments')->group(function () {
            Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
            Route::get('/payments/quote', [PaymentController::class, 'quote'])->name('payments.quote');
            Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
        });

        Route::middleware('admin.permission:payment_settings')->group(function () {
            Route::get('/payment-settings', [PaymentSettingsController::class, 'index'])->name('payment-settings.index');
            Route::post('/payment-settings', [PaymentSettingsController::class, 'update'])->name('payment-settings.update');
            Route::put('/payment-settings', [PaymentSettingsController::class, 'update'])->name('payment-settings.update.put');
        });

        Route::middleware('admin.permission:settings')->group(function () {
            Route::match(['get', 'post'], '/settings', [SettingsController::class, 'index'])->name('settings.index');
        });

        Route::middleware('admin.permission:crm')->group(function () {
            Route::get('/integrations/crm', [\App\Http\Controllers\Admin\CrmSettingsController::class, 'index'])->name('crm.index');
            Route::post('/integrations/crm', [\App\Http\Controllers\Admin\CrmSettingsController::class, 'update'])->name('crm.update');
            Route::post('/integrations/crm/test', [\App\Http\Controllers\Admin\CrmSettingsController::class, 'test'])->name('crm.test');
            Route::get('/integrations/crm/leads', [\App\Http\Controllers\Admin\CrmLeadsController::class, 'index'])->name('crm.leads');
            Route::post('/integrations/crm/leads/{lead}/retry', [\App\Http\Controllers\Admin\CrmLeadsController::class, 'retry'])->name('crm.leads.retry');
        });

        Route::middleware('super.admin')->prefix('admins')->name('admins.')->group(function () {
            Route::get('/', [AdminsController::class, 'index'])->name('index');
            Route::get('/create', [AdminsController::class, 'create'])->name('create');
            Route::post('/', [AdminsController::class, 'store'])->name('store');
            Route::get('/{admin}/edit', [AdminsController::class, 'edit'])->name('edit');
            Route::put('/{admin}', [AdminsController::class, 'update'])->name('update');
            Route::delete('/{admin}', [AdminsController::class, 'destroy'])->name('destroy');
        });
    });
});
