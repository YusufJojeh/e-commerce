<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Slide extends Model
{
    protected $fillable = [
        'position','title','subtitle','image_path','cta_label','cta_url',
        'sort_order','starts_at','ends_at','is_active'
    ];

    protected $casts = ['starts_at'=>'datetime','ends_at'=>'datetime'];

    public function scopeActive(Builder $q){ return $q->where('is_active', true); }

    public function scopeCurrent(Builder $q){
        return $q->active()
            ->where(fn($w)=>$w->whereNull('starts_at')->orWhere('starts_at','<=', now()))
            ->where(fn($w)=>$w->whereNull('ends_at')->orWhere('ends_at','>=', now()));
    }

    public function scopePosition(Builder $q, string $pos){ return $q->where('position', $pos); }
}
