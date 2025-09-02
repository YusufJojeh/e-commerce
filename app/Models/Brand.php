<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;
use App\Traits\HasImages;

class Brand extends Model
{
    use AsSource, Filterable, HasImages;

    protected $fillable = [
        'name', 'slug', 'is_external', 'is_active', 'logo_path', 'sort_order',
    ];

    // For Orchid tables
    protected $allowedSorts   = ['name', 'is_external', 'is_active', 'sort_order', 'created_at'];
    protected $allowedFilters = ['name', 'is_external', 'is_active'];

    // Computed attributes
    protected $appends = ['logo_url'];

    public function scopeActive($q){ return $q->where('is_active', true); }
    public function scopeExternal($q){ return $q->where('is_external', true); }

    /**
     * Override trait methods for brand-specific configuration
     */
    protected function getImagePathField(): string
    {
        return 'logo_path';
    }

    protected function getImageDirectory(): string
    {
        return 'brands';
    }

    protected function getImageFallback(): ?string
    {
        return 'images/placeholder-brand.png';
    }
}
