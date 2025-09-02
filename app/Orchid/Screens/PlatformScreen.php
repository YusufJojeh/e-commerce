<?php

// namespace App\Orchid\Screens;

// use Orchid\Screen\Screen;
// use Orchid\Support\Facades\Layout;

// use App\Models\Product;
// use App\Models\Category;
// use App\Models\Offer;
// use App\Models\Slide;

// class PlatformScreen extends Screen
// {
//     public function name(): ?string
//     {
//         return 'Dashboard';
//     }

//     public function description(): ?string
//     {
//         return 'Store overview & quick stats';
//     }

//     public function query(): array
//     {
//         // عدّادات آمنة سواء عندك Scopes أو لا
//         $products        = Product::count();
//         $activeProducts  = method_exists(Product::class, 'active')
//             ? Product::active()->count()
//             : Product::where('is_active', 1)->count();

//         $featured        = method_exists(Product::class, 'featured')
//             ? Product::featured()->count()
//             : Product::where('is_featured', 1)->count();

//         $external        = Product::whereHas('brand', fn($q) => $q->where('is_external', 1))->count();
//         $categories      = Category::count();

//         $offers          = method_exists(Offer::class, 'current')
//             ? Offer::current()->count()
//             : Offer::where('is_active', 1)
//                 ->where('starts_at', '<=', now())
//                 ->where('ends_at', '>=', now())
//                 ->count();

//         $slides          = method_exists(Slide::class, 'position')
//             ? Slide::position('slider')->count()
//             : Slide::where('position', 'slider')->count();

//         return compact('products','activeProducts','featured','external','categories','offers','slides');
//     }

//     public function layout(): array
//     {
//         // نعتمد Blade جزئي عبر Layout::view
//         return [
//             Layout::view('admin.orchid.dashboard'),
//         ];
//     }
// }



namespace App\Orchid\Screens;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Offer;
use Orchid\Screen\Screen;
use Orchid\Screen\Layouts;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;

class PlatformScreen extends Screen
{
    public function name(): ?string
    {
        return 'لوحة التحكم';
    }

    public function description(): ?string
    {
        return 'نظرة عامة سريعة وإجراءات مهمة';
    }

    public function query(): iterable
    {
        return [
            'stats' => [
                'products'  => Product::count(),
                'categories'=> Category::count(),
                'brands'    => Brand::count(),
                'offers'    => Offer::count(),
            ],
            // مثال: مبيعات آخر أشهر (ثابت الآن – اربطه لاحقًا بجدولك)
            'sales' => [
                ['month' => 'يناير', 'value' => 1200],
                ['month' => 'فبراير','value' => 1450],
                ['month' => 'مارس',  'value' => 1800],
                ['month' => 'أبريل', 'value' => 2100],
                ['month' => 'مايو',  'value' => 2600],
                ['month' => 'يونيو', 'value' => 3000],
            ],
        ];
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('إضافة منتج')
                ->icon('bs.plus-circle')
                ->route('platform.products.create')
                ->class('btn btn-gradient text-white'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::view('platform.partials.dashboard-hero'),
            Layout::view('platform.partials.dashboard-cards'),
            Layout::view('platform.partials.dashboard-sales'),
            Layout::view('platform.partials.dashboard-script'),
        ];
    }
}
