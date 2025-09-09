<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EnhancedPerformanceService
{
    private AdvancedCacheService $cache;

    public function __construct(AdvancedCacheService $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Get cached products with advanced optimization
     */
    public function getCachedProducts(array $filters = [], int $limit = 12, int $page = 1): array
    {
        $cacheKey = 'products.' . md5(serialize($filters)) . ".{$limit}.{$page}";

        return $this->cache->rememberWithTags($cacheKey, ['products'], function () use ($filters, $limit, $page) {
            $query = Product::with(['images' => function ($q) {
                    $q->orderBy('sort_order')->orderBy('id');
                }, 'category', 'brand'])
                ->active()
                ->select(['id', 'name', 'slug', 'short_description', 'price', 'sale_price', 'category_id', 'brand_id', 'is_featured', 'published_at']);

            // Apply filters
            if (!empty($filters['category'])) {
                $query->whereHas('category', fn($c) => $c->where('slug', $filters['category']));
            }

            if (!empty($filters['brand'])) {
                $query->whereHas('brand', fn($b) => $b->where('slug', $filters['brand']));
            }

            if (!empty($filters['search'])) {
                $query->where(function ($q) use ($filters) {
                    $q->where('name', 'like', "%{$filters['search']}%")
                      ->orWhere('short_description', 'like', "%{$filters['search']}%");
                });
            }

            if (!empty($filters['featured'])) {
                $query->featured();
            }

            if (!empty($filters['price_min'])) {
                $query->where(function ($q) use ($filters) {
                    $q->where('sale_price', '>=', $filters['price_min'])
                      ->orWhere(function ($q2) use ($filters) {
                          $q2->whereNull('sale_price')->where('price', '>=', $filters['price_min']);
                      });
                });
            }

            if (!empty($filters['price_max'])) {
                $query->where(function ($q) use ($filters) {
                    $q->where('sale_price', '<=', $filters['price_max'])
                      ->orWhere(function ($q2) use ($filters) {
                          $q2->whereNull('sale_price')->where('price', '<=', $filters['price_max']);
                      });
                });
            }

            // Apply sorting
            $sort = $filters['sort'] ?? 'latest';
            switch ($sort) {
                case 'price_low':
                    $query->orderByRaw('COALESCE(sale_price, price) ASC');
                    break;
                case 'price_high':
                    $query->orderByRaw('COALESCE(sale_price, price) DESC');
                    break;
                case 'name':
                    $query->orderBy('name');
                    break;
                case 'featured':
                    $query->orderBy('is_featured', 'desc')->latest('published_at');
                    break;
                default:
                    $query->latest('published_at');
            }

            // Get total count for pagination
            $total = $query->count();

            // Apply pagination
            $offset = ($page - 1) * $limit;
            $products = $query->offset($offset)->limit($limit)->get();

            return [
                'data' => $products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'short_description' => $product->short_description,
                        'price' => $product->price,
                        'sale_price' => $product->sale_price,
                        'effective_price' => $product->sale_price ?? $product->price,
                        'is_featured' => $product->is_featured,
                        'primary_image_url' => $product->images->first()?->url,
                        'category' => $product->category ? [
                            'id' => $product->category->id,
                            'name' => $product->category->name,
                            'slug' => $product->category->slug,
                        ] : null,
                        'brand' => $product->brand ? [
                            'id' => $product->brand->id,
                            'name' => $product->brand->name,
                            'slug' => $product->brand->slug,
                        ] : null,
                    ];
                })->toArray(),
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => $total,
                    'last_page' => ceil($total / $limit),
                    'from' => $offset + 1,
                    'to' => min($offset + $limit, $total),
                ],
            ];
        }, 3600); // Cache for 1 hour
    }

    /**
     * Get cached product details with full relationships
     */
    public function getCachedProductDetails(string $slug): ?array
    {
        $cacheKey = "product.details.{$slug}";

        return $this->cache->rememberWithTags($cacheKey, ['products', 'product-details'], function () use ($slug) {
            $product = Product::with([
                'images' => function ($q) {
                    $q->orderBy('sort_order')->orderBy('id');
                },
                'category',
                'brand',
                'offers' => function ($q) {
                    $q->where('is_active', true)->where('starts_at', '<=', now())
                      ->where('ends_at', '>=', now());
                }
            ])->where('slug', $slug)->first();

            if (!$product) {
                return null;
            }

            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'short_description' => $product->short_description,
                'description' => $product->description,
                'price' => $product->price,
                'sale_price' => $product->sale_price,
                'effective_price' => $product->sale_price ?? $product->price,
                'stock_qty' => $product->stock_qty,
                'is_featured' => $product->is_featured,
                'published_at' => $product->published_at,
                'images' => $product->images->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'url' => $image->url,
                        'alt' => $image->alt,
                        'is_primary' => $image->is_primary,
                        'sort_order' => $image->sort_order,
                    ];
                })->toArray(),
                'category' => $product->category ? [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                    'slug' => $product->category->slug,
                ] : null,
                'brand' => $product->brand ? [
                    'id' => $product->brand->id,
                    'name' => $product->brand->name,
                    'slug' => $product->brand->slug,
                ] : null,
                'offers' => $product->offers->map(function ($offer) {
                    return [
                        'id' => $offer->id,
                        'name' => $offer->name,
                        'discount_percentage' => $offer->discount_percentage,
                        'discount_amount' => $offer->discount_amount,
                    ];
                })->toArray(),
            ];
        }, 7200); // Cache for 2 hours
    }

    /**
     * Get cached navigation data
     */
    public function getCachedNavigation(): array
    {
        return $this->cache->rememberWithTags('navigation.main', ['navigation', 'categories', 'brands'], function () {
            return [
                'categories' => Category::active()
                    ->select(['id', 'name', 'slug', 'sort_order', 'parent_id'])
                    ->orderBy('sort_order')
                    ->get()
                    ->map(function ($category) {
                        return [
                            'id' => $category->id,
                            'name' => $category->name,
                            'slug' => $category->slug,
                            'sort_order' => $category->sort_order,
                            'parent_id' => $category->parent_id,
                        ];
                    })->toArray(),
                'brands' => Brand::active()
                    ->select(['id', 'name', 'slug'])
                    ->orderBy('name')
                    ->get()
                    ->map(function ($brand) {
                        return [
                            'id' => $brand->id,
                            'name' => $brand->name,
                            'slug' => $brand->slug,
                        ];
                    })->toArray(),
            ];
        }, 86400); // Cache for 24 hours
    }

    /**
     * Get cached categories with hierarchy
     */
    public function getCachedCategories(): array
    {
        return $this->cache->rememberWithTags('categories.hierarchy', ['categories'], function () {
            $categories = Category::active()
                ->select(['id', 'name', 'slug', 'description', 'parent_id', 'sort_order', 'image_path'])
                ->withCount('products')
                ->orderBy('sort_order')
                ->get();

            // Build hierarchy
            $hierarchy = [];
            $children = [];

            foreach ($categories as $category) {
                if ($category->parent_id) {
                    $children[$category->parent_id][] = [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'description' => $category->description,
                        'image_path' => $category->image_path,
                        'image_url' => $category->image_url,
                        'sort_order' => $category->sort_order,
                        'products_count' => $category->products_count ?? 0,
                        'children_count' => 0, // Child categories don't have subcategories
                    ];
                } else {
                    $hierarchy[] = [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'description' => $category->description,
                        'image_path' => $category->image_path,
                        'image_url' => $category->image_url,
                        'sort_order' => $category->sort_order,
                        'products_count' => $category->products_count ?? 0,
                        'children_count' => 0, // Will be updated after building hierarchy
                        'children' => [],
                    ];
                }
            }

            // Add children to parents and update children_count
            foreach ($hierarchy as &$parent) {
                if (isset($children[$parent['id']])) {
                    $parent['children'] = $children[$parent['id']];
                    $parent['children_count'] = count($children[$parent['id']]);
                }
            }

            return $hierarchy;
        }, 86400); // Cache for 24 hours
    }

    /**
     * Get cached brands
     */
    public function getCachedBrands(): array
    {
        return $this->cache->rememberWithTags('brands.list', ['brands'], function () {
            return Brand::active()
                ->select(['id', 'name', 'slug', 'logo_path', 'sort_order'])
                ->orderBy('name')
                ->get()
                ->map(function ($brand) {
                    return [
                        'id' => $brand->id,
                        'name' => $brand->name,
                        'slug' => $brand->slug,
                        'logo_url' => $brand->logo_url,
                        'sort_order' => $brand->sort_order,
                    ];
                })->toArray();
        }, 86400); // Cache for 24 hours
    }

    /**
     * Get cached site settings
     */
    public function getCachedSiteSettings(): array
    {
        return $this->cache->rememberWithTags('site.settings', ['settings'], function () {
            // Get all settings
            $settings = Setting::all();
            $result = [];

            foreach ($settings as $setting) {
                $result[$setting->key] = $setting->value;
            }

            // Parse JSON settings
            if (isset($result['site.social_media']) && $result['site.social_media']) {
                $result['social_media'] = json_decode($result['site.social_media'], true) ?: [];
            } else {
                $result['social_media'] = [];
            }

            if (isset($result['home.limits']) && $result['home.limits']) {
                $result['limits'] = json_decode($result['home.limits'], true) ?: [];
            } else {
                $result['limits'] = [];
            }

            return $result;
        }, 86400); // Cache for 24 hours
    }

    /**
     * Get cached search suggestions
     */
    public function getCachedSearchSuggestions(string $query, int $limit = 10): array
    {
        $cacheKey = 'search.suggestions.' . md5($query) . ".{$limit}";

        return $this->cache->remember($cacheKey, function () use ($query, $limit) {
            $suggestions = [];

            // Product name suggestions
            $products = Product::active()
                ->where('name', 'like', "%{$query}%")
                ->select(['name', 'slug'])
                ->limit($limit)
                ->get();

            foreach ($products as $product) {
                $suggestions[] = [
                    'type' => 'product',
                    'text' => $product->name,
                    'url' => route('products.show', $product->slug),
                ];
            }

            // Category suggestions
            $categories = Category::active()
                ->where('name', 'like', "%{$query}%")
                ->select(['name', 'slug'])
                ->limit(5)
                ->get();

            foreach ($categories as $category) {
                $suggestions[] = [
                    'type' => 'category',
                    'text' => $category->name,
                    'url' => route('categories.show', $category->slug),
                ];
            }

            // Brand suggestions
            $brands = Brand::active()
                ->where('name', 'like', "%{$query}%")
                ->select(['name', 'slug'])
                ->limit(5)
                ->get();

            foreach ($brands as $brand) {
                $suggestions[] = [
                    'type' => 'brand',
                    'text' => $brand->name,
                    'url' => route('brands.show', $brand->slug),
                ];
            }

            return array_slice($suggestions, 0, $limit);
        }, 1800); // Cache for 30 minutes
    }

    /**
     * Get cached featured products
     */
    public function getCachedFeaturedProducts(int $limit = 8): array
    {
        return $this->cache->rememberWithTags('products.featured', ['products', 'featured'], function () use ($limit) {
            return Product::featured()
                ->active()
                ->with(['images' => function ($q) {
                    $q->orderBy('sort_order')->orderBy('id');
                }, 'category', 'brand'])
                ->select(['id', 'name', 'slug', 'short_description', 'price', 'sale_price', 'category_id', 'brand_id'])
                ->latest('published_at')
                ->limit($limit)
                ->get()
                ->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'short_description' => $product->short_description,
                        'price' => $product->price,
                        'sale_price' => $product->sale_price,
                        'effective_price' => $product->sale_price ?? $product->price,
                        'primary_image_url' => $product->images->first()?->url,
                        'category' => $product->category ? [
                            'name' => $product->category->name,
                            'slug' => $product->category->slug,
                        ] : null,
                        'brand' => $product->brand ? [
                            'name' => $product->brand->name,
                            'slug' => $product->brand->slug,
                        ] : null,
                    ];
                })->toArray();
        }, 3600); // Cache for 1 hour
    }

    /**
     * Get cached related products
     */
    public function getCachedRelatedProducts(int $productId, int $limit = 4): array
    {
        $cacheKey = "products.related.{$productId}.{$limit}";

        return $this->cache->rememberWithTags($cacheKey, ['products', 'related'], function () use ($productId, $limit) {
            $product = Product::find($productId);
            if (!$product) {
                return [];
            }

            return Product::active()
                ->where('id', '!=', $productId)
                ->where(function ($q) use ($product) {
                    $q->where('category_id', $product->category_id)
                      ->orWhere('brand_id', $product->brand_id);
                })
                ->with(['images' => function ($q) {
                    $q->orderBy('sort_order')->orderBy('id');
                }])
                ->select(['id', 'name', 'slug', 'price', 'sale_price'])
                ->limit($limit)
                ->get()
                ->map(function ($related) {
                    return [
                        'id' => $related->id,
                        'name' => $related->name,
                        'slug' => $related->slug,
                        'price' => $related->price,
                        'sale_price' => $related->sale_price,
                        'effective_price' => $related->sale_price ?? $related->price,
                        'primary_image_url' => $related->images->first()?->url,
                    ];
                })->toArray();
        }, 3600); // Cache for 1 hour
    }

    /**
     * Warm up cache for common data
     */
    public function warmUpCache(): array
    {
        $startTime = microtime(true);
        $warmedUp = [];

        try {
            // Warm up products
            $this->getCachedProducts([], 12, 1);
            $this->getCachedProducts(['featured' => true], 8, 1);
            $warmedUp[] = 'products';

            // Warm up navigation
            $this->getCachedNavigation();
            $warmedUp[] = 'navigation';

            // Warm up categories and brands
            $this->getCachedCategories();
            $this->getCachedBrands();
            $warmedUp[] = 'categories';
            $warmedUp[] = 'brands';

            // Warm up site settings
            $this->getCachedSiteSettings();
            $warmedUp[] = 'settings';

            // Warm up featured products
            $this->getCachedFeaturedProducts();
            $warmedUp[] = 'featured_products';

        } catch (\Exception $e) {
            Log::error('Cache warm-up failed: ' . $e->getMessage());
        }

        $duration = microtime(true) - $startTime;

        return [
            'warmed_up' => $warmedUp,
            'duration' => round($duration * 1000, 2), // milliseconds
            'timestamp' => now(),
        ];
    }

    /**
     * Clear specific cache types
     */
    public function clearCache(string $type = 'all'): array
    {
        $cleared = [];

        switch ($type) {
            case 'products':
                $this->cache->invalidateByTags(['products']);
                $cleared[] = 'products';
                break;

            case 'categories':
                $this->cache->invalidateByTags(['categories']);
                $cleared[] = 'categories';
                break;

            case 'brands':
                $this->cache->invalidateByTags(['brands']);
                $cleared[] = 'brands';
                break;

            case 'settings':
                $this->cache->invalidateByTags(['settings']);
                $cleared[] = 'settings';
                break;

            case 'navigation':
                $this->cache->invalidateByTags(['navigation']);
                $cleared[] = 'navigation';
                break;

            case 'all':
                $cleared = $this->cache->clearAll();
                break;
        }

        return [
            'cleared' => $cleared,
            'timestamp' => now(),
        ];
    }

    /**
     * Get performance statistics
     */
    public function getPerformanceStats(): array
    {
        return [
            'cache_stats' => $this->cache->getStats(),
            'database_stats' => $this->getDatabaseStats(),
            'timestamp' => now(),
        ];
    }

    /**
     * Get database statistics
     */
    private function getDatabaseStats(): array
    {
        try {
            $stats = [];

            // Table sizes
            $tableSizes = DB::select("
                SELECT
                    table_name,
                    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Size_MB',
                    table_rows
                FROM information_schema.tables
                WHERE table_schema = DATABASE()
                ORDER BY (data_length + index_length) DESC
            ");

            $stats['table_sizes'] = $tableSizes;

            // Slow queries
            $slowQueries = DB::select("
                SELECT
                    query_time,
                    lock_time,
                    rows_sent,
                    rows_examined,
                    sql_text
                FROM information_schema.processlist
                WHERE command != 'Sleep'
                AND time > 5
                LIMIT 10
            ");

            $stats['slow_queries'] = $slowQueries;

            return $stats;

        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
