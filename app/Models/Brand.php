<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Brand extends Model
{
    protected $fillable = ['name','slug','is_external','logo_path','is_active','sort_order'];

    public function scopeActive(Builder $q){ return $q->where('is_active', true); }
    public function scopeExternal(Builder $q){ return $q->where('is_external', true); }
}
