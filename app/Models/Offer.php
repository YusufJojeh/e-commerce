<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Offer extends Model
{
    protected $fillable = [
        'title','description','type','value','starts_at','ends_at','is_active','banner_image','cta_url'
    ];

    protected $casts = ['starts_at'=>'datetime', 'ends_at'=>'datetime'];

    public function products() { return $this->belongsToMany(Product::class); }

    public function scopeActive(Builder $q){ return $q->where('is_active', true); }

    public function scopeCurrent(Builder $q){
        return $q->active()
            ->where(fn($w)=>$w->whereNull('starts_at')->orWhere('starts_at','<=', now()))
            ->where(fn($w)=>$w->whereNull('ends_at')->orWhere('ends_at','>=', now()));
    }
}
