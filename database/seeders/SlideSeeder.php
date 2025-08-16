<?php

namespace Database\Seeders;

use App\Models\Slide;
use Illuminate\Database\Seeder;

class SlideSeeder extends Seeder
{
    public function run(): void
    {
        Slide::updateOrCreate(
            ['position' => 'main', 'title' => 'Big Summer Sale'],
            [
                'subtitle'   => 'Up to 50% off',
                'image_path' => 'banners/hero.jpg',
                'cta_label'  => 'Shop Now',
                'cta_url'    => '/products',
                'sort_order' => 0,
                'starts_at'  => now()->subDays(2),
                'ends_at'    => now()->addDays(30),
                'is_active'  => true,
            ]
        );

        $titles = ['New Arrivals', 'Hot Deals', 'Top Rated'];
        foreach ($titles as $i => $title) {
            Slide::updateOrCreate(
                ['position' => 'slider', 'title' => $title],
                [
                    'subtitle'   => null,
                    'image_path' => "banners/slider-".($i+1).".jpg",
                    'cta_label'  => 'Browse',
                    'cta_url'    => '/products',
                    'sort_order' => $i,
                    'starts_at'  => now()->subDays(2),
                    'ends_at'    => now()->addDays(60),
                    'is_active'  => true,
                ]
            );
        }
    }
}
