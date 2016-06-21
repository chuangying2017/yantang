<?php namespace App\Services\Order;
use App\Repositories\Order\ClientOrderRepositoryContract;

interface OrderGeneratorContract {

    public function buy($user_id, $skus, $address_id = null, $type = OrderProtocol::ORDER_TYPE_OF_MALL_MAIN, $promotion_id = null);

    public function confirm($temp_order_id);

    public function useCoupon($temp_order_id, $coupon_id);

    public function buyCart($user_id, $cart_ids, $address_id);

    public function setOrderRepo(ClientOrderRepositoryContract $orderRepo);
}
