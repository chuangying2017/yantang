<?php namespace App\Services\Orders\Payments;

use App\Models\Order;
use App\Services\Orders\OrderProtocol;
use App\Services\Orders\OrderRepository;
use App\Services\Orders\Supports\PingxxService;
use App\Services\Orders\UserHelper;
use Exception;

class CheckOut {

    use UserHelper;

    public function checkout($order_no, $channel)
    {
        //读取订单信息
        $order = OrderRepository::queryOrderByOrderNo($order_no);
        $main_billing = BillingRepository::storeMainBilling($order['id'], $order['user_id'], $order['pay_amount']);
        $pingxx_data = PingxxService::generatePingppBilling($order, $main_billing, $channel);
        $pingxx_payment = $pingxx_data['pingxx_payment'];
        $charge = $pingxx_data['charge'];

        return $charge;
    }


}
