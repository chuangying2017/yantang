<?php namespace App\Http\Transformers;

use App\Models\Coupon;
use League\Fractal\TransformerAbstract;

class CouponTransformer extends TransformerAbstract {


    public function transform(Coupon $coupon)
    {
        return [
            'id'             => (int)$coupon->id,
            'name'           => $coupon->name,
            'type'           => $coupon->type,
            'detail'         => $coupon->detail,
            'content'        => (int)$coupon->content,
            'quota'          => (int)$coupon->limits->quantity_per_user,
            'roles'          => $coupon->limits->roles,
            'level'          => $coupon->limits->level,
            'quantity'       => (int)$coupon->limits->quantity,
            'amount_limit'   => (int)$coupon->limits->amount_limit,
            'category_limit' => $coupon->limits->category_limit,
            'product_limit'  => $coupon->limits->product_limit,
            'multi_use'      => (boolean)$coupon->limits->multi_use,
            'effect_time'    => $coupon->limits->effect_time,
            'expire_time'    => $coupon->limits->expire_time,
            'created_at'     => $coupon->created_at,
            'updated_at'     => $coupon->updated_at,
        ];
    }


}
