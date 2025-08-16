<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        if (!Category::exists()) {
            $this->call(CategorySeeder::class);
        }
        if (!Brand::exists()) {
            $this->call(BrandSeeder::class);
        }

        $catIds = Category::pluck('id')->all();
        $brandIds = Brand::pluck('id')->all();

        if (empty($catIds) || empty($brandIds)) {
            return;
        }

        DB::transaction(function () use ($catIds, $brandIds) {
            $now = now();
            $adjectives = ['Ultra','Pro','Smart','Eco','Max','Prime','Lite','Plus','Air','Go'];
            $nouns = ['Phone','Laptop','Headset','Backpack','Shoes','Bottle','Camera','Watch','Mixer','Router'];

            $productsToCreate = 60;
            for ($i = 1; $i <= $productsToCreate; $i++) {
                $name = $adjectives[array_rand($adjectives)] . ' ' . $nouns[array_rand($nouns)];
                $slug = Str::slug($name) . '-' . $i;

                $price = random_int(1000, 50000) / 100; // 10.00 - 500.00
                $hasSale = random_int(1, 100) <= 35;
                $sale = $hasSale ? round($price * (random_int(60, 90) / 100), 2) : null;

                $p = Product::create([
                    'category_id'       => $catIds[array_rand($catIds)],
                    'brand_id'          => $brandIds[array_rand($brandIds)],
                    'name'              => $name,
                    'slug'              => $slug,
                    'short_description' => 'Short description for ' . $name,
                    'description'       => 'Detailed description for ' . $name . '.',
                    'sku'               => strtoupper(Str::random(8)),
                    'price'             => $price,
                    'sale_price'        => $sale,
                    'stock_qty'         => random_int(0, 200),
                    'is_active'         => random_int(1, 100) <= 90,
                    'is_featured'       => random_int(1, 100) <= 25,
                    'published_at'      => $now->copy()->subDays(random_int(0, 90))->setTime(random_int(0,23), random_int(0,59)),
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ]);

                // صورة أساسية
                ProductImage::create([
                    'product_id' => $p->id,
                    'path'       => 'products/sample.jpg',
                    'alt'        => $p->name,
                    'is_primary' => true,
                    'sort_order' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                // صورتان إضافيتان
                ProductImage::create([
                    'product_id' => $p->id,
                    'path'       => 'products/sample.jpg',
                    'alt'        => $p->name.' view 2',
                    'is_primary' => false,
                    'sort_order' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                ProductImage::create([
                    'product_id' => $p->id,
                    'path'       => 'products/sample.jpg',
                    'alt'        => $p->name.' view 3',
                    'is_primary' => false,
                    'sort_order' => 2,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        });
    }
}
