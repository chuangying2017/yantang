<?php namespace App\Services\Order;
interface OrderGeneratorContract {

    public function buy($user_id, $skus, $address = null, $type = OrderProtocol::ORDER_TYPE_OF_MALL_MAIN, $promotion_id = null);

    public function confirm($temp_order_id);

    public function useCoupon($temp_order_id, $coupon_id);
}
