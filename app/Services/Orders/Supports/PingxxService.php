<?php namespace App\Services\Orders\Supports;


use App\Services\Orders\Helpers\UserHelper;
use App\Services\Orders\OrderProtocol;
use App\Services\Orders\OrderRefund;
use App\Services\Orders\Payments\PaymentInterface;
use Exception;
use Pingpp\Charge;
use Pingpp\Pingpp;

class PingxxService implements PaymentInterface {

    private static $payment_no;
    private static $order_no;
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
    private static function getPaidPackage($order_no, $amount, $user_ip, $channel)
    {
        try {
            $pingxx_payment_no = PingxxPaymentRepository::generatePingxxPaymentNo();

            self::setPingxxKey();
            self::setPaymentNo($pingxx_payment_no);
            self::setOrderNo($order_no);

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
                    "metadata"  => ['order_no' => $order_no]
                ]
            );

            return $charge;
        } catch (Exception $e) {
            info($e->getMessage());
            throw $e;
        }
    }

    protected static function getExtraData($channel)
    {
        #todo 完善支付渠道
        switch ($channel) {
            case 'alipay_wap':
                $extra = array(
                    'success_url' => env('MOBILE_PAYMENT_SUCCESS_URL'),
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
                    'result_url' => env('MOBILE_PAYMENT_SUCCESS_URL')
                );
                break;
            case 'upacp_pc':
                $extra = array(
                    'result_url' => env('PAYMENT_SUCCESS_URL')
                );
                break;
            case 'wx_pub':
                $extra = array(
                    'open_id' => get_current_auth_user_openid()
                );
                break;
            case 'wx_pub_qr':
                $extra = array(
                    'product_id' => self::getOrderNo()
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
                    'result_url'       => env('PAYMENT_SUCCESS_URL')
                );
                break;
            case 'jdpay_wap':
                $extra = array(
                    'success_url' => env('PAYMENT_SUCCESS_URL'),
                    'fail_url'    => 'http://www.yourdomain.com',
                    'token'       => 'dsafadsfasdfadsjuyhfnhujkijunhaf'
                );
                break;
            case 'alipay_pc_direct':
                $extra = array(
                    'success_url' => env('PAYMENT_SUCCESS_URL'),
                );
                break;
            default:
                $extra = [];
                break;
        }

        return $extra;
    }


    /**
     * 若已支付则返回FALSE，否则返回支付charge
     * @param $pingxx_payment_no
     * @return bool|Charge
     * @throws Exception
     */
    public static function pingxxNeedPayOrFetchCharge($pingxx_payment_no)
    {
        try {
            $payment = PingxxPaymentRepository::fetchPingxxPayment($pingxx_payment_no);

            //当前pingxx账单未支付时,主动查询确认是否已支付但未收到通知
            if (self::checkIsUnPaid($payment)) {

                $pingxx_charge = self::queryPingxxPaymentCharge($payment['charge_id']);

                if ($pingxx_charge->paid) {

                    //若当前为正式环境,但账单为测试账单,则标记为未支付
                    if (env('PINGPP_ACCOUNT_TYPE') != 'TEST' && ! $pingxx_charge->livemode) {
                        throw new \Exception('支付环境配置错误');
                    }

                    self::pingxxPaymentIsPaid($pingxx_charge);

                    return false;
                }

                return $pingxx_charge;
            }

            return false;
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * 支付成功后记录相关信息,出发pingxx支付事件
     * @param $pingxx_charge_data
     * @return mixed
     * @throws Exception
     */
    public static function pingxxPaymentIsPaid($pingxx_charge_data)
    {
        try {
            $pingxx_payment_no = $pingxx_charge_data['order_no'];
            $channel = $pingxx_charge_data['channel'];
            $transaction_no = isset($pingxx_charge_data['transaction_no']) ? $pingxx_charge_data['transaction_no'] : '';
            $pingxx_payment = PingxxPaymentRepository::pingxxPaymentPaid($pingxx_payment_no, $channel, $transaction_no);

            event(new \App\Services\Orders\Event\PingxxPaid(
                $pingxx_payment['order_id'],
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

    /**
     * 若不存在 *有效* 支付渠道记录则生成,返回支付charge,
     * @param $order
     * @param $channel
     * @return array|int
     * @throws Exception
     */
    public static function getPaidCharge($order, $channel)
    {
        $amount = $order['pay_amount'];

        //查询之前的指定支付渠道的有效申请记录
        $need_to_check_valid = true;
        $pingxx_payment = PingxxPaymentRepository::getOrderPingxxPayment($order['id'], $channel, $need_to_check_valid);
        if ( ! $pingxx_payment) {
            //若不存在则生成并返回结果
            return self::generatePingppBilling($order, $amount, $channel);
        }

        $charge = self::pingxxNeedPayOrFetchCharge($pingxx_payment);
        //若渠道已支付则不需要继续处理
        if ( ! $charge) {
            return 0;
        }

        return [
            'charge'  => $charge,
            'payment' => $pingxx_payment
        ];
    }


    //检查支付渠道信息是否有效
    protected static function checkChargeIsValid($charge)
    {
        if ($charge->time_expire < time()) {
            return false;
        }

        return true;
    }

    /**
     * 生成pingxx支付账单,获取charge并存储
     * @param $order
     * @param $amount
     * @param $channel
     * @return array|int
     * @throws Exception
     */
    public static function generatePingppBilling($order, $amount, $channel)
    {
        try {

            if ($amount > 0) {
                $user_ip = isset($_SERVER) ? $_SERVER["REMOTE_ADDR"] : env('SERVER_PUBLIC_IP');
                $order_no = $order['order_no'];
                $charge = self::getPaidPackage($order_no, $amount, $user_ip, $channel);
                $payment = PingxxPaymentRepository::storePingxxPayment($charge, $order['user_id'], $order['id']);

                return compact('payment', 'charge');
            }

            return 0;
        } catch (\Exception $e) {
            throw $e;
        }

    }

    public static function orderIsPaidByPingxx($order_id)
    {
        $payments = PingxxPaymentRepository::getOrderPingxxPayment($order_id);

        if (count($payments)) {
            foreach ($payments as $payment) {
                //订单已支付
                if ( ! self::pingxxNeedPayOrFetchCharge($payment)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param $payment
     * @return Charge
     */
    public static function queryPingxxPaymentCharge($charge_id)
    {
        self::setPingxxKey();

        return Charge::retrieve($charge_id);
    }

    /**
     * @return mixed
     */
    public static function getOrderNo()
    {
        return self::$order_no;
    }

    /**
     * @param mixed $order_no
     */
    public static function setOrderNo($order_no)
    {
        self::$order_no = $order_no;
    }

    public static function checkIsPaid($payment)
    {
        $payment = PingxxPaymentRepository::fetchPingxxPayment($payment);

        return $payment['status'] == PingxxProtocol::STATUS_OF_PAID;
    }

    public static function checkIsUnPaid($payment)
    {
        $payment = PingxxPaymentRepository::fetchPingxxPayment($payment);

        return $payment['status'] == PingxxProtocol::STATUS_OF_UNPAID;
    }

    public static function refund($order_refund, $amount, $desc = '')
    {
        $pingxx_payment = PingxxPaymentRepository::getOrderPaidPingxxPayment($order_refund['order_id']);

        $ch = self::queryPingxxPaymentCharge($pingxx_payment['charge_id']);

        /**
         *
         * {
         * "id": "re_y1u944PmfnrTHyvnL0nD0iD1",
         * "object": "refund",
         * "order_no": "y1u944PmfnrTHyvnL0nD0iD1",
         * "amount": 9,
         * "created": 1409634160,
         * "succeed": true,
         * "status": "succeeded",
         * "time_succeed": 1409634192,
         * "description": "Refund Description",
         * "failure_code": null,
         * "failure_msg": null,
         * "metadata": {},
         * "charge": "ch_L8qn10mLmr1GS8e5OODmHaL4",
         * "transaction_no": "2004450349201512090096425284"
         * }
         */

        $result = $ch->refunds->create(
            [
                'amount'      => $amount,
                'description' => $desc
            ]
        );
        $pingxx_refund_data = [
            'order_id'          => $order_refund['order_id'],
            'payment_no'        => $result['order_no'],
            'order_refund_id'   => $order_refund['id'],
            'pingxx_payment_id' => $pingxx_payment['id'],
            'refund_id'         => $result['id'],
            'charge_id'         => $result['charge'],
            'failure_code'      => $result['failure_code'] ?: '',
            'failure_msg'       => $result['failure_msg'] ?: '',
            'transaction_no'    => $result['transaction_no'],
            'amount'            => $amount,
            'description'       => $desc,
            'status'            => $result['status'],
            'time_succeed'      => $result['time_succeed']
        ];
        $pingxx_refund_payment = PingxxPaymentRepository::generateRefundPayment($pingxx_refund_data);

        return $result;
    }

    public static function refunded($data, $pingxx_payment)
    {
        //修改pingxx订单状态
        PingxxPaymentRepository::updateRefundSuccess($pingxx_payment['refund_id'], $data);

        OrderRefund::refunded($pingxx_payment['order_refund_id']);
    }

    public static function refundFail($pingxx_payment, $code, $msg)
    {
        PingxxPaymentRepository::updateRefundPaymentFail($pingxx_payment['refund_id'], $code, $msg);
        #todo 定时重试

    }


    public static function refundCallback($data)
    {
        $pingxx_payment = PingxxPaymentRepository::getRefundPingxxPaymentById($data['id']);
        if ($pingxx_payment['status'] == PingxxProtocol::STATUS_OF_REFUND_SUCCESS) {
            info('charge Succeed! no need to charge again');

            return 1;
        }
        //退款成功处理
        if (self::refundIsSucceed($data)) {
            self::refunded($data, $pingxx_payment);
        } else {
            //退款失败
            self::refundFail($pingxx_payment, $data['failure_code'], $data['failure_msg']);
        }

        exit('success');
    }

    protected static function refundIsSucceed($data)
    {
        return $data['succeed'] === true && $data['status'] === PingxxProtocol::STATUS_OF_REFUND_SUCCESS;
    }


}
