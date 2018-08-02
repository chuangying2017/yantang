<?php

namespace App\Models\Integral;

use App\Models\Promotion\PromotionAbstract;
use Illuminate\Database\Eloquent\Model;

class IntegralConvertCoupon extends Model
{
    protected $table='integral_convert_coupon';

    protected $guarded = ['id'];

    public function promotions()
    {
        return $this->belongsTo(PromotionAbstract::class,'promotions_id','id');
    }
}
