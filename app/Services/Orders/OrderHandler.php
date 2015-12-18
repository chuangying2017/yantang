<?php namespace App\Services\Orders;

use App\Services\Orders\Payments\BillingManager;
use App\Services\Orders\Payments\BillingRepository;
use Exception;

class OrderHandler {

    public static function orderIsPaid($order_id)
    {
        $order = OrderRepository::queryOrderById($order_id);

        #check main billing
        if ( ! BillingManager::checkMainBillingIsPaid($order_id)) {

            throw new \Exception('订单未支付');
        }

        if ($order['status'] == OrderProtocol::STATUS_OF_UNPAID) {
            OrderRepository::updateStatus($order_id, OrderProtocol::STATUS_OF_PAID);
            #todo 更新,拆单

        }

    }


}
