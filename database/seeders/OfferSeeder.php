<?php

namespace Database\Seeders;

use App\Models\Offer;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    public function run(): void
    {
        if (!Product::exists()) {
            $this->call(ProductSeeder::class);
        }

        $now = now();

        $summer = Offer::updateOrCreate(
            ['title' => 'Summer Sale 10%'],
            [
                'description' => 'Ten percent off selected products.',
                'type'        => 'percent',
                'value'       => 10,
                'starts_at'   => $now->copy()->subDays(5),
                'ends_at'     => $now->copy()->addDays(10),
                'is_active'   => true,
                'banner_image'=> 'banners/summer.jpg',
                'cta_url'     => '/products',
            ]
        );

        $fixed = Offer::updateOrCreate(
            ['title' => 'Flat 20 Off'],
            [
                'description' => 'Flat discount on some items.',
                'type'        => 'fixed',
                'value'       => 20,
                'starts_at'   => $now->copy()->subDays(2),
                'ends_at'     => $now->copy()->addDays(5),
                'is_active'   => true,
                'banner_image'=> 'banners/flat.jpg',
                'cta_url'     => '/products',
            ]
        );

        $ended = Offer::updateOrCreate(
            ['title' => 'Free Shipping Weekend'],
            [
                'description' => 'Ended example.',
                'type'        => 'free_shipping',
                'value'       => null,
                'starts_at'   => $now->copy()->subDays(12),
                'ends_at'     => $now->copy()->subDays(7),
                'is_active'   => true,
                'banner_image'=> 'banners/free-ship.jpg',
                'cta_url'     => '/products',
            ]
        );

        // اربط منتجات بالعروض النشطة فقط
        $ids = Product::where('is_active', true)->inRandomOrder()->limit(20)->pluck('id')->values();

        if ($ids->count() >= 10) {
            $summer->products()->sync($ids->slice(0, 10)->all());
            $fixed->products()->sync($ids->slice(10, 10)->all());
        }
    }
}
