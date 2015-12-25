<?php namespace App\Services\Orders\Supports;

use App\Models\PingxxPayment;

class PingxxPaymentRepository {

    const PAYMENT_ID_LENGTH = 26;

    /**
     * @return string
     */
    public static function generatePingxxPaymentNo()
    {
        $payment_no = mt_rand(1, 9) . substr(date('Y'), -2) . date('md') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', mt_rand(0, 99));

        return $payment_no;
    }

    public static function storePingxxPayment($charge, $user_id, $order_id)
    {
        $payment_no = $charge->order_no;
        $charge_id = $charge->id;
        $channel = $charge->channel;
        $amount = $charge->amount;

        // 生成payment
        $payment_data = [
            'payment_no' => $payment_no,
            'amount'     => $amount,
            'currency'   => PingxxProtocol::PAID_PACKAGE_CURRENCY,
            'channel'    => $channel,
            'user_id'    => $user_id,
            'order_id'   => $order_id,
            'charge_id'  => $charge_id,
            'status'     => PingxxProtocol::STATUS_OF_UNPAID,
        ];

        return PingxxPayment::create($payment_data);
    }


    public static function pingxxPaymentPaid($payment_no, $channel, $transaction_no = '')
    {
        $payment = PingxxPayment::where('payment_no', $payment_no)->where('channel', $channel)->firstOrFail();
        $payment->status = PingxxProtocol::STATUS_OF_PAID;
        $payment->transaction_no = $transaction_no;
        $payment->save();

        return $payment;
    }

    public static function pingxxPaymentPaidFail($payment_id, $error_code, $error_msg)
    {
        return PingxxPayment::where('id', $payment_id)->update(compact('error_code', 'error_msg'));
    }

    public static function fetchPingxxPayment($payment_no)
    {
        return ($payment_no instanceof PingxxPayment)
            ? $payment_no
            : PingxxPayment::where('payment_no', $payment_no)->firstOrFail();
    }

    public static function fetchPingxxPaymentByBilling($billing_id)
    {
        return PingxxPayment::where('billing_id', $billing_id)->firstOrFail();
    }

    public static function getOrderPingxxPayment($order_id, $channel = null)
    {
        $query = PingxxPayment::where('order_id', $order_id);

        if ( ! is_null($channel)) {
            return $query->where('channel', $channel)->first();
        }

        return $query->get();
    }


}
