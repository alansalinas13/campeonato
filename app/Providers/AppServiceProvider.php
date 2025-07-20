<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Models\Club;
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
        Route::bind('clubes', function ($value) {
        return Club::where('idclub', $value)->firstOrFail();
        });
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . base_path(env('GOOGLE_APPLICATION_CREDENTIALS')));
    }
}
