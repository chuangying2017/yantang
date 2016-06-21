<?php namespace App\Repositories\Order\Promotion;

use App\Models\Order\OrderPromotion;
use App\Services\Order\OrderProtocol;

class OrderPromotionRepository implements OrderPromotionContract {


    public function createOrderPromotion($order_id, $promotions_data)
    {

        foreach ($promotions_data as $promotion_data) {
            OrderPromotion::create([
                'order_id' => $order_id,
                'promotion_type' => $promotion_data['promotion_type'],
                'promotion_id' => $promotion_data['promotion_id'],
                'promotion_rule_id' => $promotion_data['id']
            ]);
        }
    }

    public function getOrderPromotion($order_id)
    {
        return OrderPromotion::where('order_id', $order_id)->get();
    }

    public function updateOrderPromotionFinish($order_promotion_id)
    {
        $order_promotion = OrderPromotion::find($order_promotion_id);
        $order_promotion->status = OrderProtocol::ORDER_PROMOTION_STATUS_OF_DONE;
        $order_promotion->save();
        return $order_promotion;
    }
}
