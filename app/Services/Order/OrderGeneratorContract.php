<?php namespace App\Services\Order;

use App\Repositories\Order\ClientOrderRepositoryContract;
use App\Repositories\Promotion\Campaign\EloquentCampaignRepository;

interface OrderGeneratorContract {

    public function buy($user_id, $skus, $type = OrderProtocol::ORDER_TYPE_OF_MALL_MAIN);

    public function confirm($temp_order_id);

    public function setAddress($temp_order_id, $address);

    public function useCoupon($temp_order_id, $coupon_id);

    public function buyCart($user_id, $cart_ids);

    public function subscribe($user_id, $skus, $weekday_type, $daytime, $start_time);

    public function buySpecialCampaign($user_id, $campaign_id, EloquentCampaignRepository $campaignRepo);

    public function setOrderRepo(ClientOrderRepositoryContract $orderRepo);
}
