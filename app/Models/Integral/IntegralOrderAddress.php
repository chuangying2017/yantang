<?php

namespace App\Models\Integral;

use Illuminate\Database\Eloquent\Model;

class IntegralOrderAddress extends Model
{
    protected $table = 'integral_order_address';

    protected $guarded = [];

    public function integral_order()
    {
        $this->belongsTo(IntegralOrder::class,'order_id','id');
    }
}
