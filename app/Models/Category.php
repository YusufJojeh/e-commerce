<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;           // 👈 مهم لـ Orchid
use Orchid\Filters\Filterable;        // 👈 للفرز/الفلترة في الجداول (اختياري لكنه مفيد)

class Category extends Model
{
    use AsSource, Filterable;

    protected $fillable = [
        'parent_id', 'name', 'slug', 'description', 'is_active', 'sort_order',
    ];

    // أسماء الأعمدة المسموح فرزها/فلترتها من جدول Orchid
    protected $allowedSorts   = ['name', 'is_active', 'sort_order', 'created_at'];
    protected $allowedFilters = ['name', 'is_active'];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
