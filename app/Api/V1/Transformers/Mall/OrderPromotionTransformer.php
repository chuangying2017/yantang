<?php namespace App\Api\V1\Transformers\Mall;

use App\Models\Order\OrderPromotion;
use League\Fractal\TransformerAbstract;

class OrderPromotionTransformer extends TransformerAbstract {

    public function transform(OrderPromotion $promotion)
    {
        return $promotion->toArray();
    }

}
