<?php namespace App\Services\Orders\Payments;


use App\Models\OrderBilling;
use App\Models\PingxxPayment;
use App\Services\Orders\OrderProtocol;
use DB;

class BillingRepository {

    const BILLING_PREFIX = 'billing_';
    const PAYMENT_ID_LENGTH = 26;

    public static function storeBilling($order_id, $user_id, $amount, $type, $resource_type, $resource_id, $status = OrderProtocol::STATUS_OF_UNPAID)
    {

        if ($amount < 0) {
            throw new \Exception('账单金额不能小于0');
        }

        $billing_no = self::generateBillingNo();
        $billing_data = compact('billing_no', 'order_id', 'user_id', 'amount', 'type', 'resource_type', 'resource_id', 'status');

        return OrderBilling::create($billing_data);
    }

    public static function storeTicketBilling($order_id, $user_id, $amount, $resource_id)
    {
        return self::storeBilling($order_id, $user_id, $amount, OrderProtocol::TYPE_OF_DISCOUNT, OrderProtocol::RESOURCE_OF_TICKET, $resource_id);
    }

    public static function storeMainBilling($order_id, $user_id, $amount, $resource_type = OrderProtocol::RESOURCE_OF_PINGXX)
    {
        self::deleteOldBilling($order_id, OrderProtocol::TYPE_OF_MAIN);

        return self::storeBilling($order_id, $user_id, $amount, OrderProtocol::TYPE_OF_MAIN, $resource_type, 0);
    }


    public static function deleteOldBilling($order_id, $type)
    {
        return OrderBilling::where('order_id', $order_id)->where('type', $type)->where('status', OrderProtocol::STATUS_OF_UNPAID)->delete();
    }

    protected static function generateBillingNo()
    {
//        $billing_no = date('YmdHis') . mt_rand(100000, 999999) . mt_rand(100000, 999999);
        $billing_no = uniqid(self::BILLING_PREFIX);
        while (self::fetchBilling($billing_no)) {
            $billing_no = uniqid(self::BILLING_PREFIX);
        }

        return $billing_no;
    }

    public static function fetchBilling($billing_no)
    {
        return $billing_no instanceof OrderBilling
            ? $billing_no
            : (
            (strpos($billing_no, self::BILLING_PREFIX) === false)
                ? OrderBilling::findOrFail($billing_no)
                : OrderBilling::where('billing_no', $billing_no)->first()
            );
    }

    public static function storePingxxPayment($user_id, $order_id, $billing_id, $amount, $charge_id, $channel)
    {
        // 生成payment
        $payment_id = self::getPingxxPaymentId();
        $payment_data = [
            'payment_id' => $payment_id,
            'amount'     => $amount,
            'currency'   => OrderProtocol::PAID_PACKAGE_CURRENCY,
            'channel'    => $channel,
            'user_id'    => $user_id,
            'order_id'   => $order_id,
            'billing_id' => $billing_id,
            'charge_id'  => $charge_id,
            'status'     => OrderProtocol::STATUS_OF_UNPAID,
        ];

        return PingxxPayment::create($payment_data);
    }

    public static function billingPaid($billing_id, $resource_id = null)
    {
        $status = OrderProtocol::STATUS_OF_PAID;

        return OrderBilling::where('id', $billing_id)->update(compact('status', 'resource_id'));
    }

    public static function pingxxPaymentPaid($payment_id, $transaction_no = '')
    {
        $status = OrderProtocol::STATUS_OF_PAID;

        return PingxxPayment::where('id', $payment_id)->update(compact('status', 'transaction_no'));
    }

    public static function fetchPingxxPayment($payment_id)
    {
        return $payment_id instanceof OrderBilling
            ? $payment_id
            : (
            (strlen($payment_id) == self::PAYMENT_ID_LENGTH)
                ? OrderBilling::findOrFail($payment_id)
                : OrderBilling::where('payment_id', $payment_id)->first()
            );
    }

    /**
     * @return string
     */
    protected static function getPingxxPaymentId()
    {
        $payment_id = date('YmdHis') . mt_rand(100000, 999999) . mt_rand(100000, 999999);
        while (self::fetchBilling($payment_id)) {
            $payment_id = date('YmdHis') . mt_rand(100000, 999999) . mt_rand(100000, 999999);
        }

        return $payment_id;
    }

}
