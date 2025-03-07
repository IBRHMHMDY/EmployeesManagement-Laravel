<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

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
        app('router')->aliasMiddleware('auth:sanctum', EnsureFrontendRequestsAreStateful::class);
        Route::middlewareGroup('api', [
            EnsureFrontendRequestsAreStateful::class,
        ]);
    }
}
