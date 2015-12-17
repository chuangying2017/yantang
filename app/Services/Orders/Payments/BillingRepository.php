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

    public static function getMainBilling($order_id)
    {
        return self::getBilling($order_id, OrderProtocol::TYPE_OF_MAIN, OrderProtocol::RESOURCE_OF_PINGXX);
    }

    public static function getBilling($order_id, $billing_type = OrderProtocol::TYPE_OF_MAIN, $billing_resource = OrderProtocol::RESOURCE_OF_PINGXX)
    {
        $query = OrderBilling::where('type', $billing_type)->where('resource_type', $billing_resource)->where('order_id', $order_id);
        if ($billing_type == OrderProtocol::TYPE_OF_MAIN) {
            return $query->first();
        }

        return $query->get();
    }


    public static function billingPaid($billing_id, $resource_id = null)
    {
        $status = OrderProtocol::STATUS_OF_PAID;

        return OrderBilling::where('id', $billing_id)->update(compact('status', 'resource_id'));
    }


}
