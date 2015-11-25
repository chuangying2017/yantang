<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model {

    protected $table = 'coupons';

    protected $guarded = ['id'];

    protected $hidden = ['deleted_at'];

    public function limits()
    {
        return $this->morphOne('App\Models\DiscountLimit', 'resource');
    }

    public function tickets()
    {
        return $this->morphOne('App\Models\Ticket', 'resource');
    }

}
