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
        while (PingxxPayment::where('payment_id', $payment_id)->count()) {
            $payment_id = date('YmdHis') . mt_rand(100000, 999999) . mt_rand(100000, 999999);
        }

        return $payment_id;
    }

    public static function storePingxxPayment($user_id, $order_id, $order_no, $billing_id, $amount, $charge_id, $channel)
    {
        // 生成payment
        $payment_no = $order_no;
        $payment_data = [
            'payment_no' => $payment_no,
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


    public static function pingxxPaymentPaid($payment_no, $transaction_no = '')
    {
        $payment = self::fetchPingxxPayment($payment_no);

        PingxxProtocol::validStatus($payment['status'], PingxxProtocol::STATUS_OF_PAID);

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


}
