<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name', 'placeholder', 'slug',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function items()
    {
        return $this->hasMany('App\Models\Item');
    }

}
