<?php

namespace App\Models\Integral;

use App\Models\Promotion\PromotionAbstract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IntegralConvertCoupon extends Model
{
    use SoftDeletes;

    protected $table='integral_convert_coupon';

    protected $guarded = ['id'];

    public function promotions()
    {
        return $this->belongsTo(PromotionAbstract::class,'promotions_id','id');
    }
}
