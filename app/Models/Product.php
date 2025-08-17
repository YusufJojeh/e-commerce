<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;

class Product extends Model
{
    use AsSource, Filterable;

    protected $fillable = [
        'category_id','brand_id','name','slug','short_description','description',
        'sku','price','sale_price','stock_qty','is_active','is_featured','published_at',
    ];

    // للفرز/الفلترة في جداول Orchid
    protected $allowedSorts   = ['name','price','sale_price','stock_qty','is_active','is_featured','published_at','created_at'];
    protected $allowedFilters = ['name','is_active','is_featured'];

    // علاقات
    public function category()   { return $this->belongsTo(Category::class); }
    public function brand()      { return $this->belongsTo(Brand::class); }
    public function images()     { return $this->hasMany(ProductImage::class); }
    public function primaryImage(){ return $this->hasOne(ProductImage::class)->where('is_primary', true); }
    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'offer_product');
    }

    // سكوبات مفيدة
    public function scopeActive($q)        { return $q->where('is_active', true); }
    public function scopeFeatured($q)      { return $q->where('is_featured', true); }
    public function scopeExternalBrand($q) { return $q->whereHas('brand', fn($b)=>$b->where('is_external', 1)); }
}
