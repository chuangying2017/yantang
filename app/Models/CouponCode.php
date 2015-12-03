<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CouponCode extends Model
{
    use SoftDeletes;

    protected $table = 'coupon_code';

    protected $guarded = ['id'];

    public function limits()
    {
        return $this->morphOne('App\Models\DiscountLimit', 'resource');
    }
}
