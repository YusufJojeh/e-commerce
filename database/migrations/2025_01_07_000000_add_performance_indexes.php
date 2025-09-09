<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Products table indexes
        Schema::table('products', function (Blueprint $table) {
            // Composite index for active and featured products
            $table->index(['is_active', 'is_featured'], 'idx_products_active_featured');
            
            // Composite index for category and brand filtering
            $table->index(['category_id', 'brand_id'], 'idx_products_category_brand');
            
            // Index for price range filtering
            $table->index(['price', 'sale_price'], 'idx_products_price_range');
            
            // Index for published date sorting
            $table->index('published_at', 'idx_products_published_at');
            
            // Index for slug lookups
            $table->index('slug', 'idx_products_slug');
            
            // Index for stock quantity
            $table->index('stock_qty', 'idx_products_stock_qty');
            
            // Index for SKU lookups
            $table->index('sku', 'idx_products_sku');
        });
        
        // Categories table indexes
        Schema::table('categories', function (Blueprint $table) {
            // Composite index for active categories with sorting
            $table->index(['is_active', 'sort_order'], 'idx_categories_active_sort');
            
            // Composite index for parent-child relationships
            $table->index(['parent_id', 'is_active'], 'idx_categories_parent_active');
            
            // Index for slug lookups
            $table->index('slug', 'idx_categories_slug');
            
            // Index for name searches
            $table->index('name', 'idx_categories_name');
        });
        
        // Brands table indexes
        Schema::table('brands', function (Blueprint $table) {
            // Composite index for active brands with name sorting
            $table->index(['is_active', 'name'], 'idx_brands_active_name');
            
            // Index for slug lookups
            $table->index('slug', 'idx_brands_slug');
            
            // Index for external brands
            $table->index('is_external', 'idx_brands_external');
        });
        
        // Product images table indexes
        Schema::table('product_images', function (Blueprint $table) {
            // Composite index for product images with sorting
            $table->index(['product_id', 'sort_order'], 'idx_product_images_product_sort');
            
            // Composite index for primary image lookups
            $table->index(['product_id', 'is_primary'], 'idx_product_images_primary');
            
            // Index for image path lookups
            $table->index('path', 'idx_product_images_path');
        });
        
        // Settings table indexes
        Schema::table('settings', function (Blueprint $table) {
            // Composite index for group and key lookups
            $table->index(['group', 'key'], 'idx_settings_group_key');
            
            // Index for group lookups
            $table->index('group', 'idx_settings_group');
        });
        
        // Users table indexes (if not already exists)
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                // Index for email lookups
                if (!$this->indexExists('users', 'idx_users_email')) {
                    $table->index('email', 'idx_users_email');
                }
                
                // Index for active users
                if (!$this->indexExists('users', 'idx_users_active')) {
                    $table->index('is_active', 'idx_users_active');
                }
            });
        }
        
        // Sessions table indexes (if using database sessions)
        if (Schema::hasTable('sessions')) {
            Schema::table('sessions', function (Blueprint $table) {
                // Index for user_id lookups
                if (!$this->indexExists('sessions', 'idx_sessions_user_id')) {
                    $table->index('user_id', 'idx_sessions_user_id');
                }
                
                // Index for last activity
                if (!$this->indexExists('sessions', 'idx_sessions_last_activity')) {
                    $table->index('last_activity', 'idx_sessions_last_activity');
                }
            });
        }
        
        // Cache table indexes (if using database cache)
        if (Schema::hasTable('cache')) {
            Schema::table('cache', function (Blueprint $table) {
                // Index for expiration lookups
                if (!$this->indexExists('cache', 'idx_cache_expiration')) {
                    $table->index('expiration', 'idx_cache_expiration');
                }
            });
        }
        
        // Cache locks table indexes (if using database cache locks)
        if (Schema::hasTable('cache_locks')) {
            Schema::table('cache_locks', function (Blueprint $table) {
                // Index for expiration lookups
                if (!$this->indexExists('cache_locks', 'idx_cache_locks_expiration')) {
                    $table->index('expiration', 'idx_cache_locks_expiration');
                }
            });
        }
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop products indexes
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_active_featured');
            $table->dropIndex('idx_products_category_brand');
            $table->dropIndex('idx_products_price_range');
            $table->dropIndex('idx_products_published_at');
            $table->dropIndex('idx_products_slug');
            $table->dropIndex('idx_products_stock_qty');
            $table->dropIndex('idx_products_sku');
        });
        
        // Drop categories indexes
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('idx_categories_active_sort');
            $table->dropIndex('idx_categories_parent_active');
            $table->dropIndex('idx_categories_slug');
            $table->dropIndex('idx_categories_name');
        });
        
        // Drop brands indexes
        Schema::table('brands', function (Blueprint $table) {
            $table->dropIndex('idx_brands_active_name');
            $table->dropIndex('idx_brands_slug');
            $table->dropIndex('idx_brands_external');
        });
        
        // Drop product images indexes
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropIndex('idx_product_images_product_sort');
            $table->dropIndex('idx_product_images_primary');
            $table->dropIndex('idx_product_images_path');
        });
        
        // Drop settings indexes
        Schema::table('settings', function (Blueprint $table) {
            $table->dropIndex('idx_settings_group_key');
            $table->dropIndex('idx_settings_group');
        });
        
        // Drop users indexes
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex('idx_users_email');
                $table->dropIndex('idx_users_active');
            });
        }
        
        // Drop sessions indexes
        if (Schema::hasTable('sessions')) {
            Schema::table('sessions', function (Blueprint $table) {
                $table->dropIndex('idx_sessions_user_id');
                $table->dropIndex('idx_sessions_last_activity');
            });
        }
        
        // Drop cache indexes
        if (Schema::hasTable('cache')) {
            Schema::table('cache', function (Blueprint $table) {
                $table->dropIndex('idx_cache_expiration');
            });
        }
        
        // Drop cache locks indexes
        if (Schema::hasTable('cache_locks')) {
            Schema::table('cache_locks', function (Blueprint $table) {
                $table->dropIndex('idx_cache_locks_expiration');
            });
        }
    }
    
    /**
     * Check if an index exists on a table
     */
    private function indexExists(string $table, string $index): bool
    {
        try {
            $indexes = Schema::getConnection()
                ->getSchemaBuilder()
                ->getIndexes($table);
                
            return collect($indexes)->contains('name', $index);
        } catch (\Exception $e) {
            return false;
        }
    }
};
