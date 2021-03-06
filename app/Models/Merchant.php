<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Merchant extends Model {

    protected $table = 'merchants';

    protected $guarded = ['id'];

    public function images()
    {
        return $this->hasMany('App\Models\Image', 'merchant_id', 'id');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product', 'merchant_id', 'id');
    }
}
