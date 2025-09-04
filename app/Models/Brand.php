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

    protected $allowedSorts   = ['name', 'is_external', 'is_active', 'sort_order', 'created_at'];
    protected $allowedFilters = ['name', 'is_external', 'is_active'];

    // إرجاع logo_url تلقائياً
    protected $appends = ['logo_url'];

    // (اختياري) تحويلات أنظف
    protected $casts = [
        'is_external' => 'boolean',
        'is_active'   => 'boolean',
        'sort_order'  => 'integer',
    ];

    public function scopeActive($q){ return $q->where('is_active', true); }
    public function scopeExternal($q){ return $q->where('is_external', true); }

    /**
     * Accessor مطلوب لـ logo_url
     */
    public function getLogoUrlAttribute(): ?string
    {
        // إذا ما في مسار، جرّب fallback من التrait
        $path = $this->logo_path ?: $this->getImageFallback();

        if (empty($path)) {
            return null;
        }

        // إذا كان المسار رابط كامل http(s) خلّيه كما هو
        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        // وإلا اعتبره محفوظ على disk=public
        return Storage::disk('public')->url($path);
    }

    /**
     * تهيئة إعدادات الصور الخاصة بالـ Brand (تستخدمها HasImages)
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
