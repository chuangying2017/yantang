<?php namespace App\Repositories\Pay\Pingxx;

use App\Models\Pay\PingxxPayment;
use App\Repositories\Pay\ChargeRepositoryContract;
use App\Repositories\Pay\PaymentRepositoryContract;
use App\Services\Pay\Events\PingxxPaymentIsFail;
use App\Services\Pay\Pingxx\PingxxProtocol;
use Carbon\Carbon;
use Pingpp\Charge;
use Pingpp\Pingpp;

class PingxxPaymentRepository implements ChargeRepositoryContract, PaymentRepositoryContract {

    public function __construct()
    {
        self::setPingxxKey();
    }

    private static function setPingxxKey()
    {
        Pingpp::setApiKey(config('services.pingxx.api_key'));
    }

    public function createCharge($amount, $order_no, $channel = null)
    {
        $payment_no = self::getPaymentNo($order_no, $channel);
        return Charge::create([
            "amount" => $amount,
            "channel" => $channel,
            "order_no" => $payment_no,
            "currency" => PingxxProtocol::PAID_PACKAGE_CURRENCY,
            "client_ip" => \Request::ip(),
            "app" => ["id" => config('services.pingxx.app_id')],
            "subject" => config('app.name') . '订单',
            "body" => $payment_no,
            "extra" => self::getExtraData($channel, $payment_no),
        ]);
    }

    protected static function getPaymentNo($order_no, $channel)
    {
        return $order_no . PingxxProtocol::getChannelNo($channel);
    }


    protected static function getExtraData($channel, $payment_no = null)
    {
        $mobile_success = config('services.pingxx.mobile_success');
        $mobile_cancel = config('services.pingxx.mobile_cancel');
        $pc_success = config('services.pingxx.pc_success');
        $pc_cancel = config('services.pingxx.pc_cancel');

        switch ($channel) {
            case 'alipay_wap':
                $extra = [
                    'success_url' => $mobile_success,
                    'cancel_url' => $mobile_cancel
                ];
                break;
            case 'upmp_wap':
                $extra = [
                    'result_url' => $mobile_success
                ];
                break;
            case 'bfb_wap':
                $extra = [
                    'result_url' => $mobile_success,
                    'bfb_login' => true
                ];
                break;
            case 'upacp_wap':
                $extra = [
                    'result_url' => $mobile_success
                ];
                break;
            case 'upacp_pc':
                $extra = [
                    'result_url' => $pc_success
                ];
                break;
            case 'wx_pub':
                $extra = [
                    'open_id' => access()->getProviderId('weixin')
                ];
                break;
            case 'wx_pub_qr':
                $extra = [
                    'product_id' => $payment_no
                ];
                break;
            case 'yeepay_wap':
                $extra = [
                    'product_category' => '1',
                    'identity_id' => 'your identity_id',
                    'identity_type' => 1,
                    'terminal_type' => 1,
                    'terminal_id' => 'your terminal_id',
                    'user_ua' => 'your user_ua',
                    'result_url' => $mobile_success
                ];
                break;
            case 'jdpay_wap':
                $extra = [
                    'success_url' => $mobile_success,
                    'fail_url' => 'http://www.yourdomain.com',
                    'token' => 'dsafadsfasdfadsjuyhfnhujkijunhaf'
                ];
                break;
            case 'alipay_pc_direct':
                $extra = [
                    'success_url' => $pc_success,
                ];
                break;
            default:
                $extra = [];
                break;
        }

        return $extra;
    }

    /**
     * @param $charge_id
     * @return Charge
     */
    public function getCharge($charge_id)
    {
        return is_string($charge_id) ? Charge::retrieve($charge_id) : $charge_id;
    }

    public function createPayment($charge, $billing_id, $billing_type)
    {
        return PingxxPayment::create([
            'billing_id' => $billing_id,
            'billing_type' => $billing_type,
            'charge_id' => $charge->id,
            'payment_no' => $charge->order_no,
            'amount' => $charge->amount,
            'currency' => $charge->currency,
            'app' => $charge->app,
            'channel' => $charge->channel,
            'livemode' => $charge->livemode,
            'time_expire' => $charge->time_expire,
            'paid' => false
        ]);
    }


    public function setPaymentAsPaid($payment_no, $transaction_no)
    {
        $payment = $this->getPayment($payment_no);

        if ($payment->paid) {
            return $payment;
        }

        $payment->transaction_no = $transaction_no;
        $payment->paid = 1;
        $payment->pay_at = Carbon::now();
        $payment->save();
        return $payment;
    }

    public function deletePayment($payment_no)
    {
        return PingxxPayment::where('payment_no', $payment_no)->delete();
    }

    public function getPayment($payment_no)
    {
        if ($payment_no instanceof PingxxPayment) {
            return $payment_no;
        }

        return PingxxPayment::where('payment_no', $payment_no)->firstOrFail();
    }

    public function getPayChannel($payment_no)
    {
        $payment = $this->getPayment($payment_no);
        return $payment->channel;
    }

    public function getPayType($payment_no)
    {
        return PingxxPayment::class;
    }

	/**
     * @param $billing_id
     * @param $billing_type
     * @param null $channel
     * @return PingxxPayment
     */
    public function getPaymentByBilling($billing_id, $billing_type, $channel = null)
    {
        if (!is_null($channel)) {
            return PingxxPayment::query()
                ->where('billing_id', $billing_id)
                ->where('billing_type', $billing_type)
                ->where('channel', $channel)->first();
        }

        return PingxxPayment::query()
            ->where('billing_id', $billing_id)
            ->where('billing_type', $billing_type)
            ->get();
    }


    public function getChargeTransaction($charge_id)
    {
        $charge = $this->getCharge($charge_id);

        return $charge->transaction_no;
    }


    public function getChargePayment($charge_id)
    {
        $charge = $this->getCharge($charge_id);

        return $charge->order_no;
    }

    public function setPaymentAsFail($payment_no, $failure_code, $failure_msg)
    {
        $payment = $this->getPayment($payment_no);
        $payment->failure_code = $failure_code;
        $payment->failure_msg = $failure_msg;
        $payment->save();

        event(new PingxxPaymentIsFail($payment));

        return $payment;
    }
}
