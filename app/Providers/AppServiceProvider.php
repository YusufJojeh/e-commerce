<?php

namespace App\Providers;

use App\Helpers\SiteHelper;
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
        // Use Bootstrap pagination views
        \Illuminate\Pagination\Paginator::useBootstrapFive();
        
        // Set locale from session
        if (session()->has('locale')) {
            app()->setLocale(session('locale'));
        }
        
        // Set HTML direction based on locale
        view()->composer('*', function ($view) {
            $view->with('htmlDir', app()->getLocale() === 'ar' ? 'rtl' : 'ltr');
        });
    }
}
