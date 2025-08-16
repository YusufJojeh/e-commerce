<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key','value','group'];

    public static function get(string $key, $default=null){
        $row = static::query()->where('key',$key)->first();
        if(!$row) return $default;
        $decoded = json_decode($row->value, true);
        return json_last_error() === JSON_ERROR_NONE ? $decoded : $row->value;
    }

    public static function put(string $key, $value, ?string $group=null): void {
        static::updateOrCreate(['key'=>$key], [
            'value' => is_array($value) ? json_encode($value) : $value,
            'group' => $group,
        ]);
    }
}
