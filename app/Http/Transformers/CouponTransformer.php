<?php namespace App\Http\Transformers;

use App\Models\Promotion\Coupon;
use App\Models\Promotion\Ticket;
use App\Services\Marketing\MarketingProtocol;
use League\Fractal\TransformerAbstract;

class CouponTransformer extends TransformerAbstract {


    public function transform(Coupon $coupon)
    {
        if ($coupon->type == MarketingProtocol::DISCOUNT_TYPE_OF_CASH) {
            $content = display_price($coupon->content);
        } else {
            $content = display_discount($coupon->content);
        }

        return array_merge(
            [
                'coupon_id'  => (int)$coupon->id,
                'name'       => $coupon->name,
                'type'       => $coupon->type,
                'detail'     => $coupon->detail,
                'content'    => $content,
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
                'amount_limit'   => display_price($limits->amount_limit),
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
