<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ثيم ديناميكي قادم من الإعدادات (يؤثر على اللوحة والواجهة إن استخدمته هناك أيضاً)
        $dashboard->registerResource('stylesheets', '/theme.css');

        // أي تخصيصات إضافية للوحة (اختياري)
        $dashboard->registerResource('stylesheets', '/css/platform-theme.css');

        // Crystal theme enhancements for Orchid platform
        $dashboard->registerResource('stylesheets', '/css/crystal-platform.css');

        // Custom JavaScript for platform animations
        $dashboard->registerResource('scripts', '/js/crystal-platform.js');
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        return [
            // ====== الرئيسي
            Menu::make('Dashboard')
                ->icon('bs.house')
                ->route('platform.index'),

            // ====== الكاتالوج
            Menu::make('Catalog')->title('Catalog'),

            Menu::make('Categories')
                ->icon('bs.collection')
                ->route('platform.categories.list'),

            Menu::make('Brands')
                ->icon('bs.tags')
                ->route('platform.brands.list'),

            Menu::make('Products')
                ->icon('bs.box')
                ->route('platform.products.list'),

            Menu::make('Offers')
                ->icon('bs.ticket-perforated')
                ->route('platform.offers.list'),

            Menu::make('Slides')
                ->icon('bs.images')
                ->route('platform.slides.list'),

            // ====== إعدادات الموقع
            Menu::make('Site')->title('Site'),

            Menu::make('Appearance')                // شاشة التحكم بالثيم/الألوان والشعارات
                ->icon('bs.palette')
                ->route('platform.appearance')
                ->permission('manage.appearance'),

            Menu::make('Settings')                  // إعدادات عامة
                ->icon('bs.gear')
                ->route('platform.settings')
                ->permission('manage.settings'),

            Menu::make('Home Settings')             // إعدادات الصفحة الرئيسية (إن كنت تستخدمها)
                ->icon('bs.sliders')
                ->route('platform.site.home')
                ->permission('manage.settings'),

            // ====== الصلاحيات
            Menu::make(__('Users'))
                ->icon('bs.people')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Access Controls')),

            Menu::make(__('Roles'))
                ->icon('bs.shield')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles'),
        ];
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),

            ItemPermission::group('Settings')
                ->addPermission('manage.settings', 'Manage Site Settings')
                ->addPermission('manage.appearance', 'Manage Appearance & Branding'),
        ];
    }
}
