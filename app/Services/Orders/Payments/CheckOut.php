<?php namespace App\Services\Orders\Payments;

use App\Models\Order;
use App\Services\Orders\OrderProtocol;
use App\Services\Orders\OrderRepository;
use App\Services\Orders\UserHelper;
use Exception;
use Pingpp\Charge;
use Pingpp\Pingpp;

class CheckOut {

    use UserHelper;

    public function checkout($order_no, $channel)
    {
        //读取订单信息
        $order = OrderRepository::queryOrderByOrderNo($order_no);
        $main_billing = BillingRepository::storeMainBilling($order['id'], $order['user_id'], $order['pay_amount']);
        $pingxx_data = self::generatePingppBilling($order, $main_billing, $channel);
        $pingxx_payment = $pingxx_data['pingxx_payment'];
        $charge = $pingxx_data['charge'];

        return $charge;
    }

    /**
     * 获取支付创建对象
     * @param $paymentId
     * @param $userId
     * @param $deposit
     * @param $userIP
     * @param $accountType
     * @return Charge
     */
    public static function getPaidPackage($order, $billing_no, $amount, $user_IP, $channel, $pingxx_account = OrderProtocol::PINGXX_ACCOUNT)
    {
        try {
            Pingpp::setApiKey(getenv($pingxx_account . '_PINGPP_APIKEY'));
            $order_no = $order['order_no'];


            $charge = Charge::create(
                array_merge(
                    array(
                        "amount"    => $amount,
                        "order_no"  => $billing_no,
                        "currency"  => trans(OrderProtocol::PAID_PACKAGE_CURRENCY),
                        "client_ip" => $user_IP,
                        "app"       => array("id" => getenv($pingxx_account . '_PINGPP_APPID')),
                        "subject"   => $order['title'],
                        "body"      => $order['title'],
                        "extra"     => self::getExtraData($channel, $order['user_id']),
                        "metadata"  => array("billing_no" => $billing_no, 'order_no' => $order_no, 'order_id' => $order['id'])
                    )
                )
            );

            return $charge;
        } catch (Exception $e) {
            info($e->getMessage());
            throw $e;
        }
    }

    protected function getExtraData($channel, $user_id = null)
    {
        switch ($channel) {
            case OrderProtocol::PINGXX_WAP_CHANNEL_WECHAT:
                $openid = self::getUserOpenid($user_id);
                $extra_data = [
                    "extra" => [
                        "trade_type" => 'JSAPI',
                        "open_id"    => $openid,
                    ]
                ];
                break;
            default:
                $extra_data = [];
                break;
        }

        return $extra_data;
    }


    public static function generatePingppBilling($order, $main_billing, $channel)
    {

        try {
            $amount = $main_billing['amount'];

            if ($amount > 0) {
                $user_ip = isset($_SERVER) ? $_SERVER["REMOTE_ADDR"] : env('SERVER_PUBLIC_IP');
                $charge = self::getPaidPackage($order, $main_billing['billing_no'], $amount, $user_ip, $channel);

                $pingxx_payment = BillingRepository::storePingxxPayment($order['user_id'], $order['id'], $main_billing['id'], $amount, $charge->id, $channel);

                return compact('pingxx_payment', 'charge');
            }

            return 0;
        } catch (Exception $e) {
            info($e->getMessage());
            throw new $e;
        }

    }

}
