<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $now = now();

            $rows = [
                ['name' => 'MyStore',  'is_external' => false, 'logo' => 'logos/sample.png'],
                ['name' => 'Apple',    'is_external' => true,  'logo' => 'logos/sample.png'],
                ['name' => 'Samsung',  'is_external' => true,  'logo' => 'logos/sample.png'],
                ['name' => 'Sony',     'is_external' => true,  'logo' => 'logos/sample.png'],
                ['name' => 'Lenovo',   'is_external' => true,  'logo' => 'logos/sample.png'],
                ['name' => 'Dell',     'is_external' => true,  'logo' => 'logos/sample.png'],
                ['name' => 'HP',       'is_external' => true,  'logo' => 'logos/sample.png'],
                ['name' => 'Nike',     'is_external' => true,  'logo' => 'logos/sample.png'],
                ['name' => 'Adidas',   'is_external' => true,  'logo' => 'logos/sample.png'],
                ['name' => 'Zara',     'is_external' => true,  'logo' => 'logos/sample.png'],
            ];

            $order = 0;
            foreach ($rows as $r) {
                Brand::updateOrCreate(
                    ['slug' => Str::slug($r['name'])],
                    [
                        'name'       => $r['name'],
                        'is_external'=> $r['is_external'],
                        'is_active'  => true,
                        'logo_path'  => $r['logo'],
                        'sort_order' => $order++,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
            }
        });
    }
}
