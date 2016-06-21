<?php namespace App\Repositories\Order\Promotion;
interface OrderPromotionRepositoryContract {

    public function createOrderPromotion($order_id, $promotion_data);

    public function getOrderPromotion($order_id);

    public function updateOrderPromotionFinish($order_promotion_id);

}
