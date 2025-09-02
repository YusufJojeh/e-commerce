<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix ProductImage paths
        DB::statement("UPDATE product_images SET path = REPLACE(path, '2025/products/', 'products/') WHERE path LIKE '2025/products/%'");

        // Fix Category paths
        DB::statement("UPDATE categories SET image_path = REPLACE(image_path, '2025/categories/', 'categories/') WHERE image_path LIKE '2025/categories/%'");

        // Fix Brand paths
        DB::statement("UPDATE brands SET logo_path = REPLACE(logo_path, '2025/brands/', 'brands/') WHERE logo_path LIKE '2025/brands/%'");

        // Fix Slide paths
        DB::statement("UPDATE slides SET image_path = REPLACE(image_path, '2025/slides/', 'slides/') WHERE image_path LIKE '2025/slides/%'");

        // Fix Offer paths
        DB::statement("UPDATE offers SET banner_image = REPLACE(banner_image, '2025/offers/', 'offers/') WHERE banner_image LIKE '2025/offers/%'");

        // Fix Settings paths
        DB::statement("UPDATE settings SET value = REPLACE(value, '2025/branding/', 'branding/') WHERE value LIKE '2025/branding/%'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is not reversible as it's a data fix
    }
};
