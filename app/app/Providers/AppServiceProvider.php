<?php

namespace App\Providers;

use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Tests\Support\PdoSchemaMySqlConnection;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services
     */
    public function register(): void
    {
        $files = glob(app_path('Helpers/Shorthands').'/*.php');

        foreach ($files as $key => $file) {
            require_once $file;
        }

        // Lets the testing database load schema dumps without the mysql client binary.
        if ($this->app->environment('testing')) {
            Connection::resolverFor('mysql', fn ($pdo, $database, $prefix, $config) => new PdoSchemaMySqlConnection($pdo, $database, $prefix, $config));
        }
    }

    /**
     * Bootstrap any application services
     */
    public function boot(): void
    {
        // Register Vite image helper
        Vite::macro('image', fn (string $asset) => Vite::asset("resources/images/{$asset}"));

        // Prevent lazy loading in non-production environments
        Model::preventLazyLoading(! app()->isProduction());
    }
}
