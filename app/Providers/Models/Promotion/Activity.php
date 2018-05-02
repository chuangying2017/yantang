<?php

namespace App\Models\Promotion;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model {

    protected $table = 'activity';

    protected $guarded = [];

    public function setCouponsAttribute($value)
    {
        $this->attributes['coupons'] = json_encode($value);
    }

    public function getCouponsAttribute()
    {
        return json_decode($this->attributes['coupons']);
    }

}
