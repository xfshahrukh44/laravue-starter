<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['title', 'image', 'expiration_date'];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    
}
