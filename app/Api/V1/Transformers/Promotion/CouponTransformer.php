<?php namespace App\Api\V1\Transformers\Promotion;

use App\Api\V1\Transformers\Traits\SetInclude;
use App\Models\Promotion\Coupon;
use League\Fractal\TransformerAbstract;

class CouponTransformer extends TransformerAbstract {

    use SetInclude;

    protected $availableIncludes = ['rules'];

    public function transform(Coupon $coupon)
    {
        $this->setInclude($coupon);

        $data = [
            'id' => $coupon['id'],
            'name' => $coupon['name'],
            'content' => $coupon['content'],
            'desc' => $coupon['desc'],
            'cover_image' => $coupon['cover_image'],
            'start_time' => $coupon['start_time'],
            'end_time' => $coupon['end_time'],
            'active' => $coupon['active'],
            'created_at' => $coupon['created_at'],
            'updated_at' => $coupon['updated_at'],
        ];

        if ($coupon->relationLoaded('counter')) {
            $data['counter'] = [
                'effect_days' => $coupon['counter']['effect_days'],
                'total' => $coupon['counter']['total'],
                'dispatch' => $coupon['counter']['dispatch'],
                'used' => $coupon['counter']['used'],
            ];
        }

        return $data;
    }

    public function includeRules(Coupon $coupon)
    {
        return $this->collection($coupon->rules, new PromotionRuleTransformer(), true);
    }
}
