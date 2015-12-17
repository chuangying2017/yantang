<?php namespace App\Services\Orders\Payments;

use App\Models\Order;
use App\Services\Orders\Helpers\UserHelper;
use App\Services\Orders\OrderProtocol;
use App\Services\Orders\OrderRepository;
use App\Services\Orders\Supports\PingxxProtocol;
use App\Services\Orders\Supports\PingxxService;
use Exception;

class CheckOut {

    use UserHelper;

    public static function checkout($order_no, $channel, $agent)
    {
        try {

            //读取订单信息,生产新的支付订单
            $order = OrderRepository::queryOrderByOrderNo($order_no);
            $main_billing = BillingRepository::storeMainBilling($order['id'], $order['user_id'], $order['pay_amount']);

            $amount = $main_billing['amount'];

            if ($amount > 0) {
                //pingxx 生成支付渠道订单,返回支付信息
                $pingxx_data = PingxxService::generatePingppBilling($order, $main_billing, $channel, $agent);
                $payment = $pingxx_data['payment'];
                $charge = $pingxx_data['charge'];

                return $charge;
            }

            return 1;

        } catch (Exception $e) {
            throw $e;
        }

    }

    public static function channel($agent)
    {
        return PingxxProtocol::agent($agent);
    }


}
