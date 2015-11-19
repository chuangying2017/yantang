<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupons';

    protected $guarded = ['id'];

    public function limits()
    {
        return $this->morphOne('App\Models\DiscountLimit', 'resource');
    }
}
