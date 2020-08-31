<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'unit_id', 'has_measurement', 'predefined_size',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function basket_items()
    {
        return $this->hasMany('App\Models\BasketItem');
    }

    public function unit()
    {
        return $this->belongsTo('App\Models\Unit');
    }
}
