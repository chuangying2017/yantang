<?php namespace App\Services\Orders\Payments;

use App\Models\Order;
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

    public static function checkout($order_no, $channel, $agent)
    {
        try {

            //读取订单信息,生产新的支付订单
            $order = OrderRepository::queryOrderByOrderNo($order_no);
            $amount = $order['pay_amount'];
            $main_billing = BillingRepository::storeMainBilling($order['id'], $order['user_id'], $amount);

            if ($amount > 0) {
                //pingxx 生成支付渠道订单,返回支付信息


                $pingxx_data = PingxxService::generatePingppBilling($order, $main_billing, $amount, $channel, $agent);
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

            //检查订单状态是否需要支付
            if ($order['status'] == OrderProtocol::STATUS_OF_UNPAID) {

                //订单金额小于0无需支付
                if ($order['pay_amount'] <= 0) {
                    return false;
                }

                $billing = BillingRepository::getMainBilling($order['id']);
                //无历史账单则需要支付
                if ( ! $billing) {
                    return true;
                }

                if ($billing['status'] == OrderProtocol::STATUS_OF_PAID) {
                    event(new OrderIsPaid($billing['order_id']));
                }

                //历史账单查询微支付
                $pingxx_need_paid = PingxxService::checkPingxxPaymentNeedPayOrGetChargeByBilling($billing['id']);
                if ( $pingxx_need_paid) {
                    return $pingxx_need_paid;
                }
            }

            return false;
        } catch (\Exception $e) {
            throw $e;
        }

    }


}
