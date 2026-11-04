<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web-services.php'));

            Route::middleware('web')
                ->group(base_path('routes/web-auth.php'));

            Route::middleware(['web', 'auth'])
                ->group(base_path('routes/web-user-dashboard.php'));

            Route::middleware(['web', 'auth'])
                ->group(base_path('routes/web-seller-dashboard.php'));

            Route::middleware('web')
                ->group(base_path('routes/web-ecommerce.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
