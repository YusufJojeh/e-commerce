<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;
use App\Services\ImageService;

class Product extends Model
{
    use AsSource, Filterable;

    protected $fillable = [
        'category_id','brand_id','name','slug','short_description','description',
        'sku','price','sale_price','stock_qty','is_active','is_featured','published_at',
    ];

    // Type casting for interface
    protected $casts = [
        'is_active'     => 'bool',
        'is_featured'   => 'bool',
        'published_at'  => 'datetime',
        'price'         => 'decimal:2',
        'sale_price'    => 'decimal:2',
    ];

    // Computed attributes returned automatically with the model
    protected $appends = ['primary_image_url', 'effective_price'];

    // For sorting/filtering in Orchid tables
    protected $allowedSorts   = ['name','price','sale_price','stock_qty','is_active','is_featured','published_at','created_at'];
    protected $allowedFilters = ['name','is_active','is_featured'];

    // Relationships
    public function category()     { return $this->belongsTo(Category::class); }
    public function brand()        { return $this->belongsTo(Brand::class); }

    // Order images by default (first sort_order then id)
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'offer_product');
    }

    // Scopes
    public function scopeActive($q)        { return $q->where('is_active', true); }
    public function scopeFeatured($q)      { return $q->where('is_featured', true); }
    public function scopeExternalBrand($q) { return $q->whereHas('brand', fn($b)=>$b->where('is_external', 1)); }

    /**
     * Get the primary image URL for the product
     */
    public function getPrimaryImageUrlAttribute(): ?string
    {
        $imageService = app(ImageService::class);

        $primary = $this->primaryImage()->first();
        if ($primary && $primary->path) {
            $url = $imageService->getUrl($primary->path);
            // Always use 127.0.0.1:8000 for local development
            if (str_contains($url, 'localhost')) {
                $url = str_replace('localhost', '127.0.0.1:8000', $url);
            }
            return $url;
        }

        $first = $this->images()->orderBy('sort_order')->first();
        if ($first && $first->path) {
            $url = $imageService->getUrl($first->path);
            // Always use 127.0.0.1:8000 for local development
            if (str_contains($url, 'localhost')) {
                $url = str_replace('localhost', '127.0.0.1:8000', $url);
            }
            return $url;
        }

        return asset('images/placeholder-product.png');
    }

    /**
     * Effective price (sale_price if valid and less than base price)
     */
    protected function effectivePrice(): Attribute
    {
        return Attribute::get(function () {
            $price = (float) $this->price;
            $sale  = is_null($this->sale_price) ? null : (float) $this->sale_price;

            return ($sale !== null && $sale >= 0 && $sale < $price) ? $sale : $price;
        });
    }

    /**
     * Delete images and files when deleting product (additional protection alongside admin screen)
     */
    protected static function booted()
    {
        static::deleting(function (Product $product) {
            foreach ($product->images as $img) {
                $img->deleteImageFile();
            }
            $product->images()->delete();
        });
    }
}
