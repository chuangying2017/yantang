<?php namespace App\Services\Orders\Supports;


use App\Services\Orders\Helpers\UserHelper;
use App\Services\Orders\Payments\PaymentInterface;
use Exception;
use Pingpp\Charge;
use Pingpp\Pingpp;

class PingxxService implements PaymentInterface {

    private static $payment_no;
    use UserHelper;

    public static function setPaymentNo($payment_no)
    {
        self::$payment_no = $payment_no;
    }

    public static function setPingxxKey()
    {
        Pingpp::setApiKey(env(env('PINGPP_ACCOUNT_TYPE') . '_PINGPP_APIKEY'));
    }

    public static function getPaymentNo()
    {
        return self::$payment_no;
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
    private static function getPaidPackage($order, $pingxx_payment_no, $billing_no, $amount, $user_IP, $channel)
    {
        try {
            $order_no = $order['order_no'];

            self::setPingxxKey();
            self::setPaymentNo($pingxx_payment_no);
            $charge = Charge::create(
                [
                    "amount"    => $amount,
                    "channel"   => $channel,
                    "order_no"  => $pingxx_payment_no,
                    "currency"  => PingxxProtocol::PAID_PACKAGE_CURRENCY,
                    "client_ip" => $user_IP,
                    "app"       => ["id" => env(env('PINGPP_APP_NAME') . '_PINGPP_APPID')],
                    "subject"   => $order['title'],
                    "body"      => $order['title'],
                    "extra"     => self::getExtraData($channel),
                    "metadata"  => ["billing_no" => $billing_no, 'order_no' => $order_no, 'order_id' => $order['id']]
                ]
            );

            return $charge;
        } catch (Exception $e) {
            info($e->getMessage());
            throw $e;
        }
    }

    protected function getExtraData($channel)
    {
        #todo 完善支付渠道
        switch ($channel) {
            case 'alipay_wap':
                $extra = array(
                    'success_url' => 'http://www.yourdomain.com/success',
                    'cancel_url'  => 'http://www.yourdomain.com/cancel'
                );
                break;
            case 'upmp_wap':
                $extra = array(
                    'result_url' => 'http://www.yourdomain.com/result?code='
                );
                break;
            case 'bfb_wap':
                $extra = array(
                    'result_url' => 'http://www.yourdomain.com/result?code=',
                    'bfb_login'  => true
                );
                break;
            case 'upacp_wap':
                $extra = array(
                    'result_url' => 'http://www.yourdomain.com/result'
                );
                break;
            case 'wx_pub':
                $extra = array(
                    'open_id' => 'Openid'
                );
                break;
            case 'wx_pub_qr':
                $extra = array(
                    'product_id' => self::getPaymentNo()
                );
                break;
            case 'yeepay_wap':
                $extra = array(
                    'product_category' => '1',
                    'identity_id'      => 'your identity_id',
                    'identity_type'    => 1,
                    'terminal_type'    => 1,
                    'terminal_id'      => 'your terminal_id',
                    'user_ua'          => 'your user_ua',
                    'result_url'       => 'http://www.yourdomain.com/result'
                );
                break;
            case 'jdpay_wap':
                $extra = array(
                    'success_url' => 'http://www.yourdomain.com',
                    'fail_url'    => 'http://www.yourdomain.com',
                    'token'       => 'dsafadsfasdfadsjuyhfnhujkijunhaf'
                );
                break;
            default:
                $extra = [];
                break;
        }

        return $extra;
    }


    public static function generatePingppBilling($order, $main_billing, $channel, $agent)
    {

        try {
            PingxxProtocol::validChannel($channel, $agent);

            $amount = $main_billing['amount'];
            if ($amount > 0) {
                $user_ip = isset($_SERVER) ? $_SERVER["REMOTE_ADDR"] : env('SERVER_PUBLIC_IP');
                $pingxx_payment_id = PingxxPaymentRepository::getPingxxPaymentId();
                $charge = self::getPaidPackage($order, $pingxx_payment_id, $main_billing['billing_no'], $amount, $user_ip, $channel);
                $payment = PingxxPaymentRepository::storePingxxPayment($order['user_id'], $order['id'], $main_billing['id'], $amount, $charge->id, $channel, $pingxx_payment_id);

                return compact('payment', 'charge');
            }

            return 0;
        } catch (\Exception $e) {
            throw $e;
        }

    }

    public static function checkPingxxPaymentIsPaidByBilling($billing_id)
    {
        $payment = PingxxPaymentRepository::fetchPingxxPaymentByBilling($billing_id);

        return self::checkPingxxPaymentIsPaid($payment['id']);
    }

    public static function checkPingxxPaymentIsPaid($pingxx_payment_no)
    {
        self::setPingxxKey();
        $payment = PingxxPaymentRepository::fetchPingxxPayment($pingxx_payment_no);
        $result = Charge::retrieve($payment->charge_id);
        dd($result);
    }

    public static function handlePingxxChargeEvent($data)
    {

    }

    public static function pingxxPaymentIsPaid($pingxx_payment_id, $transaction_no)
    {
        try {
            $payment = PingxxPaymentRepository::fetchPingxxPayment($pingxx_payment_id);

            PingxxProtocol::validStatus($payment['status'], PingxxProtocol::STATUS_OF_PAID);

            PingxxPaymentRepository::pingxxPaymentPaid($pingxx_payment_id, $transaction_no);

        } catch (\Exception $e) {
            throw $e;
        }

    }

    public static function pingxxPaymentPaidFail($pingxx_payment_id, $failure_code, $failure_msg)
    {

        PingxxPaymentRepository::pingxxPaymentPaidFail($pingxx_payment_id, $failure_code, $failure_msg);

    }


}
