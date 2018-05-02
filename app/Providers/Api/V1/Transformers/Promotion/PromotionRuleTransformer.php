<?php namespace App\Api\V1\Transformers\Promotion;

use App\Models\Promotion\Rule;
use League\Fractal\TransformerAbstract;

class PromotionRuleTransformer extends TransformerAbstract {

    public function transform(Rule $rule)
    {
        return [
            'id' => $rule['id'],
            'name' => $rule['name'],
            'desc' => $rule['desc'],
            'promotion' => [
                'id' => $rule['promotion_id'],
            ],
            'qualify' => [
                'type' => $rule['qua_type'],
                'quantity' => $rule['qua_quantity'],
                'values' => to_array($rule['qua_content'])
            ],
            'items' => [
                'type' => $rule['item_type'],
                'values' => to_array($rule['item_content']),
            ],
            'range' => [
                'type' => $rule['range_type'],
                'min' => $rule['range_min'],
                'max' => $rule['range_max']
            ],
            'discount' => [
                'type' => $rule['discount_resource'],
                'mode' => $rule['discount_mode'],
                'value' => json_decode($rule['discount_content'])
            ],
            'weight' => $rule['weight'],
            'multi' => $rule['multi_able'],
        ];
    }

}
