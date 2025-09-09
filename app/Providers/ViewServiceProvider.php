<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share site settings with all views
        View::composer('*', function ($view) {
            try {
                // Get site name from settings
                $siteName = Setting::get('site.name', 'MyStore');
                
                // Get all site settings for social media and content
                $settings = [];
                $allSettings = Setting::all();
                
                foreach ($allSettings as $setting) {
                    $settings[$setting->key] = $setting->value;
                }
                
                // Parse JSON settings
                if (isset($settings['site.social_media']) && $settings['site.social_media']) {
                    $settings['social_media'] = json_decode($settings['site.social_media'], true) ?: [];
                } else {
                    $settings['social_media'] = [];
                }
                
                if (isset($settings['home.limits']) && $settings['home.limits']) {
                    $settings['limits'] = json_decode($settings['home.limits'], true) ?: [];
                } else {
                    $settings['limits'] = [];
                }
                
                $view->with([
                    'siteName' => $siteName,
                    'settings' => $settings,
                ]);
            } catch (\Exception $e) {
                // Fallback if settings fail to load
                $view->with([
                    'siteName' => 'MyStore',
                    'settings' => [
                        'social_media' => [],
                        'limits' => [],
                    ],
                ]);
            }
        });
    }
}
