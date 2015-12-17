<?php namespace App\Services\Orders\Supports;

use App\Models\PingxxPayment;

class PingxxPaymentRepository {

    const PAYMENT_ID_LENGTH = 26;

    /**
     * @return string
     */
    public static function getPingxxPaymentId()
    {
        $payment_id = date('YmdHis') . mt_rand(100000, 999999) . mt_rand(100000, 999999);
        #todo payment_no
        while (PingxxPayment::where('payment_id', $payment_id)->count()) {
            $payment_id = date('YmdHis') . mt_rand(100000, 999999) . mt_rand(100000, 999999);
        }

        return $payment_id;
    }

    public static function storePingxxPayment($user_id, $order_id, $billing_id, $amount, $charge_id, $channel, $payment_id = null)
    {
        // 生成payment
        $payment_id = is_null($payment_id) ? self::getPingxxPaymentId() : $payment_id;
        $payment_data = [
            'payment_id' => $payment_id,
            'amount'     => $amount,
            'currency'   => PingxxProtocol::PAID_PACKAGE_CURRENCY,
            'channel'    => $channel,
            'user_id'    => $user_id,
            'order_id'   => $order_id,
            'billing_id' => $billing_id,
            'charge_id'  => $charge_id,
            'status'     => PingxxProtocol::STATUS_OF_UNPAID,
        ];

        return PingxxPayment::create($payment_data);
    }


    public static function pingxxPaymentPaid($payment_id, $transaction_no = '')
    {
        $status = PingxxProtocol::STATUS_OF_PAID;

        return PingxxPayment::where('id', $payment_id)->update(compact('status', 'transaction_no'));
    }

    public static function pingxxPaymentPaidFail($payment_id, $error_code, $error_msg)
    {
        return PingxxPayment::where('id', $payment_id)->update(compact('error_code', 'error_msg'));
    }

    public static function fetchPingxxPayment($payment_id)
    {
        return ($payment_id instanceof PingxxPayment)
            ? $payment_id
            : (
            (strlen($payment_id) == self::PAYMENT_ID_LENGTH)
                ? PingxxPayment::where('payment_id', $payment_id)->first()
                : PingxxPayment::findOrFail($payment_id)
            );
    }

    public static function fetchPingxxPaymentByBilling($billing_id)
    {
        return PingxxPayment::where('billing_id', $billing_id)->firstOrFail();
    }


}
