<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;


class Basket extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'user_id'
    ];
    
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function basket_items()
    {
        return $this->hasMany('App\Models\BasketItem');
    }
}
