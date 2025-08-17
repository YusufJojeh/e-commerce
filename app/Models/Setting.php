<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Setting extends Model
{
    use AsSource;

    public $timestamps = true;
    protected $fillable = ['group','key','value'];

    // helper get('key', default) إن لم تكن موجودة
    public static function get($key, $default = null)
    {
        $row = static::where('key', $key)->first();
        return $row ? $row->value : $default;
    }
}
