<?php

namespace App\Models\RedEnvelope;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RedEnvelopeRule extends Model {

    use SoftDeletes;
    
    protected $table = 'red_rules';

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
