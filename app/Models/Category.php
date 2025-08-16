<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    protected $fillable = ['parent_id','name','slug','description','is_active','sort_order'];

    public function parent(){ return $this->belongsTo(Category::class,'parent_id'); }
    public function children(){ return $this->hasMany(Category::class,'parent_id'); }

    public function scopeActive(Builder $q){ return $q->where('is_active', true); }
}

