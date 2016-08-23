<?php namespace App\API\V1\Transformers\Promotion;

use App\Models\Promotion\Coupon;
use League\Fractal\TransformerAbstract;

class CouponTransformer extends TransformerAbstract {

    public function transform(Coupon $coupon)
    {
        return array_only($coupon->toArray(), [
            'id',
            'name',
            'cover_image',
            'desc',
            'start_time',
            'end_time',
            'active',
        ]);
    }

}
