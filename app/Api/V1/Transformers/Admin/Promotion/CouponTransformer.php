<?php namespace App\API\V1\Transformers\Admin\Promotion;

use App\Models\Promotion\Coupon;
use League\Fractal\TransformerAbstract;

class CouponTransformer extends TransformerAbstract {

    public function transform(Coupon $coupon)
    {
        return $coupon->toArray();
    }

}
