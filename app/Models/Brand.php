<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;

class Brand extends Model
{
    use AsSource, Filterable;

    protected $fillable = [
        'name', 'slug', 'is_external', 'is_active', 'logo_path', 'sort_order',
    ];

    // Optional but handy for Orchid tables:
    protected $allowedSorts   = ['name', 'is_external', 'is_active', 'sort_order', 'created_at'];
    protected $allowedFilters = ['name', 'is_external', 'is_active'];
    public function scopeActive(Builder $q){ return $q->where('is_active', true); }
    public function scopeExternal(Builder $q){ return $q->where('is_external', true); }
}
