<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponCode extends Model
{
    protected $table = 'coupon_code';

    protected $guarded = ['id'];

    public function limits()
    {
        return $this->morphOne('App\Models\DiscountLimit', 'resource');
    }
}
