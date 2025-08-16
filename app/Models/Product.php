<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    protected $fillable = [
        'category_id','brand_id','name','slug','short_description','description',
        'sku','price','sale_price','stock_qty','is_active','is_featured','published_at'
    ];

    protected $casts = ['published_at' => 'datetime'];

    // Relations
    public function category(){ return $this->belongsTo(Category::class); }
    public function brand(){ return $this->belongsTo(Brand::class); }
    public function images(){ return $this->hasMany(ProductImage::class); }
    public function primaryImage(){ return $this->hasOne(ProductImage::class)->where('is_primary', true); }

    // Scopes
    public function scopeActive(Builder $q){ return $q->where('is_active', true); }
    public function scopeFeatured(Builder $q){ return $q->where('is_featured', true); }
    public function scopeLatestPublished(Builder $q){ return $q->orderByDesc('published_at'); }
    public function scopeExternalBrand(Builder $q){
        return $q->whereHas('brand', fn($b)=>$b->where('is_external', true)->where('is_active', true));
    }

    // Helper: show sale price if set, else base price
    protected function displayPrice(): Attribute {
        return Attribute::get(fn()=> $this->sale_price ?? $this->price);
    }
    public function offers(){ return $this->belongsToMany(Offer::class); }

}
