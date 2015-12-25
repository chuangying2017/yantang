<?php namespace App\Services\Orders\Supports;


use App\Services\Orders\Helpers\UserHelper;
use App\Services\Orders\OrderProtocol;
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
    private static function getPaidPackage($order_no, $billing_id, $amount, $user_ip, $channel)
    {
        try {
            $pingxx_payment_no = $order_no;

            self::setPingxxKey();
            self::setPaymentNo($pingxx_payment_no);
            $charge = Charge::create(
                [
                    "amount"    => $amount,
                    "channel"   => $channel,
                    "order_no"  => $pingxx_payment_no,
                    "currency"  => PingxxProtocol::PAID_PACKAGE_CURRENCY,
                    "client_ip" => $user_ip,
                    "app"       => ["id" => env(env('PINGPP_APP_NAME') . '_PINGPP_APPID')],
                    "subject"   => '东方丽人订单',
                    "body"      => '订单号:' . $order_no,
                    "extra"     => self::getExtraData($channel),
                    "metadata"  => ["billing_id" => $billing_id, 'order_no' => $order_no]
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


    public static function generatePingppBilling($order, $main_billing, $amount, $channel, $agent)
    {

        try {
            PingxxProtocol::validChannel($channel, $agent);

            if ($amount > 0) {
                $user_ip = isset($_SERVER) ? $_SERVER["REMOTE_ADDR"] : env('SERVER_PUBLIC_IP');
                $order_no = $order['order_no'];
                $billing_id = $main_billing['id'];
                $charge = self::getPaidPackage($order_no, $billing_id, $amount, $user_ip, $channel);
                $payment = PingxxPaymentRepository::storePingxxPayment($order['user_id'], $order['id'], $order_no, $billing_id, $amount, $charge->id, $channel);

                return compact('payment', 'charge');
            }

            return 0;
        } catch (\Exception $e) {
            throw $e;
        }

    }

    public static function checkPingxxPaymentNeedPayOrGetChargeByBilling($billing_id)
    {
        $payment = PingxxPaymentRepository::fetchPingxxPaymentByBilling($billing_id);

        return self::getPingxxChargeIfIsNotPaid($payment['payment_no']);
    }

    public static function getPingxxChargeIfIsNotPaid($pingxx_payment_no)
    {
        try {
            $payment = PingxxPaymentRepository::fetchPingxxPayment($pingxx_payment_no);

            if ($payment['status'] == OrderProtocol::STATUS_OF_UNPAID) {
                self::setPingxxKey();
                $pay_result = Charge::retrieve($payment->charge_id);

                if ($pay_result->paid) {

                    //若为非测试环境,但账单为测试账单,则标记为未支付
                    if (env('PINGPP_ACCOUNT_TYPE') != 'TEST' && ! $pay_result->livemode) {
                        throw new \Exception('支付环境配置错误');
                    }

                    self::pingxxPaymentIsPaid($pay_result);

                    return false;
                }

                return $pay_result;
            }

            return false;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function handlePingxxChargeEvent($data)
    {

    }

    public static function pingxxPaymentIsPaid($pingxx_charge_data)
    {
        try {
            $pingxx_payment_no = $pingxx_charge_data['order_no'];
            $transaction_no = isset($pingxx_charge_data['transaction_no']) ? $pingxx_charge_data['transaction_no'] : '';
            $pingxx_payment = PingxxPaymentRepository::pingxxPaymentPaid($pingxx_payment_no, $transaction_no);

            event(new \App\Services\Orders\Event\PingxxPaid(
                $pingxx_payment['order_id'],
                $pingxx_payment['billing_id'],
                $pingxx_payment['id']
            ));

            return $pingxx_payment;
        } catch (\Exception $e) {
            throw $e;
        }

    }

    public static function pingxxPaymentPaidFail($pingxx_payment_id, $failure_code, $failure_msg)
    {

        PingxxPaymentRepository::pingxxPaymentPaidFail($pingxx_payment_id, $failure_code, $failure_msg);

    }


}
