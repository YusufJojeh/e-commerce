<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;

class ProductImage extends Model
{
    use AsSource, Filterable; // 👈 مهم لـ Orchid

    protected $fillable = [
        'product_id',
        'path',
        'alt',
        'is_primary',
        'sort_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    // (اختياري) للفرز/الفلترة في جداول Orchid
    protected $allowedSorts   = ['sort_order', 'is_primary', 'created_at'];
    protected $allowedFilters = ['alt', 'is_primary'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
