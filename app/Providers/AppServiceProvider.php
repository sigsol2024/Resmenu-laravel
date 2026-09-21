<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $baseline = database_path('migrations/baseline');
        if (is_dir($baseline)) {
            $this->loadMigrationsFrom($baseline);
        }

        $this->guardStagingDatabase();
        $this->validateProductionConfig();
        $this->guardDebugRoutesInProduction();
        $this->registerRestaurantActivityTouches();

        \Illuminate\Support\Facades\View::composer(
            ['layouts.manager', 'layouts.admin', 'layouts.marketing', 'layouts.auth-marketing'],
            \App\View\Composers\SiteBrandingComposer::class
        );
        \Illuminate\Support\Facades\View::composer('layouts.manager', \App\View\Composers\ManagerLayoutComposer::class);
        \Illuminate\Support\Facades\View::composer('layouts.admin', \App\View\Composers\AdminLayoutComposer::class);

        $appUrl = rtrim((string) config('app.url'), '/');
        if ($appUrl !== '') {
            \Illuminate\Support\Facades\URL::forceRootUrl($appUrl);
        }
        // Shared hosting / reverse proxy: signed magic links must match https APP_URL.
        if (str_starts_with($appUrl, 'https://')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        Blade::directive('resmenuIcon', function (string $expression): string {
            return "<?php echo \\App\\Support\\ResmenuIcons::icon($expression); ?>";
        });
        Blade::directive('resmenuPasswordToggle', function (string $expression): string {
            return "<?php echo \\App\\Support\\ResmenuIcons::passwordToggle($expression); ?>";
        });

        \Illuminate\Support\Facades\Gate::policy(\App\Models\Restaurant::class, \App\Policies\RestaurantPolicy::class);

        \Illuminate\Pagination\Paginator::defaultView('vendor.pagination.legacy');
        \Illuminate\Pagination\Paginator::defaultSimpleView('vendor.pagination.legacy-simple');
    }

    /**
     * Keep restaurants.last_activity_at fresh for the 30-day inactivity rule.
     */
    private function registerRestaurantActivityTouches(): void
    {
        $touch = static function ($model): void {
            $restaurantId = (int) ($model->restaurant_id ?? 0);
            if ($restaurantId <= 0) {
                return;
            }
            try {
                app(\App\Services\RestaurantLifecycleService::class)->touchActivity($restaurantId);
            } catch (\Throwable) {
                // Avoid breaking writes if lifecycle service/DB is unavailable mid-boot.
            }
        };

        \App\Models\MenuItem::saved($touch);
        \App\Models\MenuItem::deleted($touch);
        \App\Models\Category::saved($touch);
        \App\Models\Category::deleted($touch);
        \App\Models\Section::saved($touch);
        \App\Models\Section::deleted($touch);
        \App\Models\Order::created($touch);
        \App\Models\TableReservation::created($touch);
    }

    private function guardStagingDatabase(): void
    {
        if ($this->app->runningInConsole() && in_array($this->app->environment(), ['testing'], true)) {
            return;
        }

        $db = (string) config('database.connections.mysql.database');
        if ($this->app->environment(['local', 'staging']) && $db === 'sigsolmenu_resmenu') {
            throw new \RuntimeException(
                'Refusing to run: DB_DATABASE is production (sigsolmenu_resmenu). Use sigsolmenu_resmenu_laravel for staging.'
            );
        }

        if ($this->app->environment('production') && $db !== 'sigsolmenu_resmenu') {
            throw new \RuntimeException(
                'Refusing to run: production must use DB_DATABASE=sigsolmenu_resmenu.'
            );
        }
    }

    private function validateProductionConfig(): void
    {
        if ($this->app->environment('testing')) {
            return;
        }

        if (! $this->app->environment(['production', 'staging'])) {
            return;
        }

        if ($this->app->environment('production') && config('app.debug')) {
            throw new \RuntimeException('APP_DEBUG must be false in production.');
        }

        $encryptionKey = (string) config('resmenu.payment_encryption_key');
        if ($encryptionKey === '' || $encryptionKey === 'your-32-character-secret-key-here') {
            throw new \RuntimeException('PAYMENT_ENCRYPTION_KEY must be set to a secure value.');
        }

        if (! $this->app->environment('production')) {
            return;
        }

        if ((string) config('resmenu.app_hmac_secret') === '') {
            throw new \RuntimeException('APP_HMAC_SECRET must be set in production.');
        }

        if (! config('session.secure')) {
            Log::warning('SESSION_SECURE_COOKIE should be true in production (HTTPS).');
        }

        $sameSite = strtolower((string) config('session.same_site', 'lax'));
        if (! in_array($sameSite, ['lax', 'strict'], true)) {
            Log::warning('SESSION_SAME_SITE should be lax or strict in production.');
        }

        if (config('resmenu.recaptcha_site_key') === '' || config('resmenu.recaptcha_secret_key') === '') {
            Log::warning('RECAPTCHA_SITE_KEY and RECAPTCHA_SECRET_KEY should be set in production.');
        }

        if ((string) config('resmenu.reg_otp_bounce_webhook_secret') === '') {
            Log::warning('REG_OTP_BOUNCE_WEBHOOK_SECRET is not set; email suppression webhook will reject all traffic.');
        }

        if (! str_starts_with((string) config('app.url'), 'https://')) {
            Log::warning('APP_URL should use HTTPS in production.');
        }
    }

    private function guardDebugRoutesInProduction(): void
    {
        if (! $this->app->environment('production') || $this->app->environment('testing')) {
            return;
        }

        if (! $this->app->routesAreCached()) {
            foreach (Route::getRoutes() as $route) {
                $uri = $route->uri();
                if (str_starts_with($uri, '_ignition') || str_starts_with($uri, 'ignition')) {
                    throw new \RuntimeException(
                        'Debug routes are registered in production. Deploy with composer install --no-dev.'
                    );
                }
            }
        }
    }
}
