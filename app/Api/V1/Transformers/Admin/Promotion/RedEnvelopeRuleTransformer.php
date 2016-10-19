<?php namespace App\Api\V1\Transformers\Admin\Promotion;

use App\Models\RedEnvelope\RedEnvelopeRule;
use App\Repositories\RedEnvelope\RedEnvelopeProtocol;
use League\Fractal\TransformerAbstract;

class RedEnvelopeRuleTransformer extends TransformerAbstract {

    public function transform(RedEnvelopeRule $rule)
    {
        if ($rule->coupon_list) {
            $this->defaultIncludes = ['coupons'];
        }

        $data = [
            'id' => $rule['id'],
            'name' => $rule['name'],
            'desc' => $rule['desc'],
            'start_time' => $rule['start_time'],
            'end_time' => $rule['end_time'],
            'type' => $rule['type'],
            'type_name' => RedEnvelopeProtocol::type($rule['type']),
            'quantity' => $rule['quantity'],
            'effect_days' => $rule['effect_days'],
            'total' => $rule['total'],
            'dispatch' => $rule['dispatch'],
            'status' => $rule['status'],
        ];

        return $data;
    }

    public function includeCoupons(RedEnvelopeRule $rule)
    {
        return $this->collection($rule->coupon_list, new CouponTransformer(), true);
    }

}
