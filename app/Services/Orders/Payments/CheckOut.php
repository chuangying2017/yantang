<?php namespace App\Services\Orders\Payments;

use App\Models\Order\Order;
use App\Services\Orders\Event\OrderIsPaid;
use App\Services\Orders\Helpers\UserHelper;
use App\Services\Orders\OrderProtocol;
use App\Services\Orders\OrderRepository;
use App\Services\Orders\OrderService;
use App\Services\Orders\Supports\PingxxProtocol;
use App\Services\Orders\Supports\PingxxService;
use Exception;

class CheckOut {

    use UserHelper;

    public static function checkout($order_no, $channel)
    {
        try {

            //读取订单信息,生产新的支付订单
            $order = OrderRepository::queryOrderByOrderNo($order_no);
            $amount = $order['pay_amount'];

            //在线支付
            if ($amount > 0) {
                //获取渠道的支付charge
                $pingxx_data = PingxxService::getPaidCharge($order, $channel);
                if ( ! $pingxx_data) {
                    return false;
                }

                $payment = $pingxx_data['payment'];
                $charge = $pingxx_data['charge'];

                return $charge;
            }

            return false;
        } catch (Exception $e) {
            throw $e;
        }

    }

    public static function channel($agent)
    {
        return PingxxProtocol::agent($agent);
    }


    /**
     * 检查订单是否需要支付,需要返回 True
     * @param $user_id
     * @param $order_no
     * @return bool
     * @throws \Exception
     */
    public static function orderNeedPay($user_id, $order_no)
    {
        try {
            $order = OrderService::authOrder($user_id, $order_no);

            $billing = BillingManager::storeOrderMainBilling($order['id'], $order['user_id'], $order['pay_amount']);

            if ($order['status'] == OrderProtocol::STATUS_OF_UNPAID) {

                if ($billing['status'] == OrderProtocol::STATUS_OF_UNPAID) {
                    if (PingxxService::orderIsPaidByPingxx($order['id'])) {
                        return false;
                    }
                }

                //订单账单已支付但订单状态未改变,或者订单金额为0,则触发订单已支付事件
                if ($billing['status'] == OrderProtocol::STATUS_OF_PAID || $order['pay_amount'] <= 0) {
                    event(new OrderIsPaid($billing['order_id']));

                    return false;
                }
            }

            return true;
        } catch (\Exception $e) {
            throw $e;
        }

    }


}
