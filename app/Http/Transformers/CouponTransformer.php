<?php namespace App\Http\Transformers;

use App\Models\Coupon;
use App\Models\Ticket;
use League\Fractal\TransformerAbstract;

class CouponTransformer extends TransformerAbstract {


    public function transform(Coupon $coupon)
    {

        return array_merge(
            [
                'coupon_id'  => (int)$coupon->id,
                'name'       => $coupon->name,
                'type'       => $coupon->type,
                'detail'     => $coupon->detail,
                'content'    => (int)$coupon->content,
                'created_at' => $coupon->created_at->toDateTimeString()
            ], self::getLimits($coupon));
    }

    protected static function getLimits($coupon)
    {
        if (isset($coupon->limits) && $coupon->limits) {
            $limits = $coupon->limits;

            return [
                'quota'          => (int)$limits->quantity_per_user,
                'roles'          => $limits->roles,
                'level'          => $limits->level,
                'quantity'       => (int)$limits->quantity,
                'amount_limit'   => (int)$limits->amount_limit,
                'category_limit' => $limits->category_limit,
                'product_limit'  => $limits->product_limit,
                'multi_use'      => (boolean)$limits->multi_use,
                'effect_time'    => $limits->effect_time,
                'expire_time'    => $limits->expire_time,
            ];
        }

        return [];
    }

}
