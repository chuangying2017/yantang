<?php namespace App\Services\Orders\Payments;


use App\Models\OrderBilling;
use App\Services\Orders\OrderProtocol;
use DB;

class PaymentRepository {


    public static function storeBilling($order_id, $user_id, $amount, $type, $resource_type, $resource_id, $status = OrderProtocol::STATUS_OF_UNPAID)
    {
        $billing_no = self::generateTicketNo();
        $billing_data = compact('billing_no', 'order_id', 'user_id', 'amount', 'type', 'resource_type', 'resource_id', 'status');
        DB::table('order_billing')->insert($billing_data);
    }

    protected static function generateTicketNo()
    {
        $billing_no = date('YmdHis') . mt_rand(100000, 999999) . mt_rand(100000, 999999);
        while (self::queryByBillingNo($billing_no)) {
            $billing_no = date('YmdHis') . mt_rand(100000, 999999) . mt_rand(100000, 999999);
        }

        return $billing_no;
    }

    public static function queryByBillingNo($billing_no)
    {
        return OrderBilling::where('billing_no', $billing_no)->first();
    }


}
