<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSlug;

class Tag extends Model
{
    use HasSlug;

    protected $fillable = ['name'];
    
    protected $hidden = ['pivot'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }

    public static function findOrCreate($values)
    {
        $tags = collect($values)->map(function ($value) {
            if ($value instanceof self) {
                return $value;
            }

            return static::findFromString($value);
        });

        return is_string($values) ? $tags->first() : $tags;
    }

    protected static function findFromString(string $name)
    {
        return static::query()
            ->where('name', $name)
            ->first();
    }
}
