<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;

class Category extends Model
{
    use AsSource, Filterable;

    protected $fillable = [
        'parent_id', 'name', 'slug', 'description', 'is_active', 'sort_order',
    ];

    // لأوركيد (اختياري)
    protected $allowedSorts   = ['name', 'is_active', 'sort_order', 'created_at'];
    protected $allowedFilters = ['name', 'is_active'];

    // علاقات
    public function parent()   { return $this->belongsTo(self::class, 'parent_id'); }
    public function children() { return $this->hasMany(self::class, 'parent_id'); }

    /* ==== السكوبات المطلوبة للهوم ==== */

    // يعيد التصنيفات المفعّلة فقط
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    // يعيد التصنيفات الرئيسية فقط (بدون أب)
    public function scopeTopLevel($q)
    {
        return $q->whereNull('parent_id');
    }
}
