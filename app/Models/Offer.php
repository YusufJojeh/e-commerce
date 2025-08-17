<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;

class Offer extends Model
{
    use AsSource, Filterable;

    protected $fillable = [
        'title','description','type','value','starts_at','ends_at','is_active','banner_image','cta_url',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
        'value'     => 'float',
    ];

    // table name is "offer_product" by default; adjust if yours is different
    public function products()
    {
        return $this->belongsToMany(Product::class, 'offer_product');
    }

    // handy scope if not already present
    public function scopeCurrent($q)
    {
        $now = now();
        return $q->where('is_active', 1)
                 ->where(function ($qq) use ($now) {
                    $qq->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
                 })
                 ->where(function ($qq) use ($now) {
                    $qq->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
                 });
    }

    // For Orchid tables (optional)
    protected $allowedSorts   = ['title','type','value','starts_at','ends_at','is_active','created_at'];
    protected $allowedFilters = ['title','type','is_active'];
}
