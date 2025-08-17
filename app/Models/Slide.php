<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;

class Slide extends Model
{
    use AsSource, Filterable;

    protected $fillable = [
        'position','title','subtitle','image_path',
        'cta_label','cta_url','sort_order',
        'starts_at','ends_at','is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
    ];

    // For Orchid tables (optional)
    protected $allowedSorts   = ['position','title','sort_order','starts_at','ends_at','is_active','created_at'];
    protected $allowedFilters = ['position','title','is_active'];

    // Useful scopes used in your app
    public function scopePosition($q, string $pos) { return $q->where('position', $pos); }

    public function scopeCurrent($q)
    {
        $now = now();
        return $q->where('is_active', 1)
                 ->where(function ($qq) use ($now) { $qq->whereNull('starts_at')->orWhere('starts_at','<=',$now); })
                 ->where(function ($qq) use ($now) { $qq->whereNull('ends_at')->orWhere('ends_at','>=',$now); });
    }
}
