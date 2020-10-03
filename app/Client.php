<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTags;

class Client extends Model
{
    use HasTags;

    protected $fillable = ['uuid'];

    public function sheets()
    {
        return $this->hasMany('App\Sheet');
    }

    public function todos()
    {
        return $this->hasMany('App\Todo');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
