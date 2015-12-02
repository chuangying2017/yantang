<?php namespace App\Services\Orders\Supports;


use App\Services\Orders\OrderProtocol;
use App\Services\Orders\Payments\BillingRepository;
use App\Services\Orders\UserHelper;
use Exception;
use Pingpp\Charge;
use Pingpp\Pingpp;

class PingxxService {

    use UserHelper;

    /**
     * 获取支付创建对象
     * @param $paymentId
     * @param $userId
     * @param $deposit
     * @param $userIP
     * @param $accountType
     * @return Charge
     */
    public static function getPaidPackage($order, $pingxx_payment_no, $billing_no, $amount, $user_IP, $channel, $pingxx_account = OrderProtocol::PINGXX_ACCOUNT)
    {
        try {
            Pingpp::setApiKey(getenv($pingxx_account . '_PINGPP_APIKEY'));
            $order_no = $order['order_no'];


            $charge = Charge::create(
                array_merge(
                    array(
                        "amount"    => $amount,
                        "order_no"  => $pingxx_payment_no,
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
                $pingxx_payment_id = BillingRepository::getPingxxPaymentId();
                $charge = self::getPaidPackage($order, $pingxx_payment_id, $main_billing['billing_no'], $amount, $user_ip, $channel);
                $pingxx_payment = BillingRepository::storePingxxPayment($order['user_id'], $order['id'], $main_billing['id'], $amount, $charge->id, $channel, $pingxx_payment_id);

                return compact('pingxx_payment', 'charge');
            }

            return 0;
        } catch (Exception $e) {
            info($e->getMessage());
            throw new $e;
        }

    }

    public static function checkPingxxPaymentIsPaid($pingxx_payment_id)
    {
        $payment = BillingRepository::fetchPingxxPayment($pingxx_payment_id);
        $result = Charge::retrieve($payment->charge_id);


    }

    public static function handlePingxxChargeEvent($data)
    {
        
    }

    public static function pingxxPaymentIsPaid($pingxx_payment_id, $transaction_no)
    {
        try {
            $payment = BillingRepository::fetchPingxxPayment($pingxx_payment_id);

            OrderProtocol::validStatus($payment['status'], OrderProtocol::STATUS_OF_PAID);

            BillingRepository::pingxxPaymentPaid($pingxx_payment_id, $transaction_no);

        } catch (\Exception $e) {
            throw $e;
        }

    }

    public static function pingxxPaymentPaidFail($pingxx_payment_id, $failure_code, $failure_msg)
    {

        BillingRepository::pingxxPaymentPaidFail($pingxx_payment_id, $failure_code, $failure_msg);


    }




}
