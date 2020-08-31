<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;

class BasketItem extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'basket_id', 'item_id', 'quantity'
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function basket()
    {
        return $this->belongsTo('App\Models\Basket');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Item');
    }
}
