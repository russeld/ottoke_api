<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    public static function bootHasSlug()
    {
        static::saving(function (Model $model) {
           $model->generateSlug();
        });
    }

    protected function generateSlug()
    {
        $slugger = '\Illuminate\Support\Str::slug';
        $slug = call_user_func($slugger, $this->getAttribute('name'));
        $this->setAttribute('slug', $slug);
    }
}
