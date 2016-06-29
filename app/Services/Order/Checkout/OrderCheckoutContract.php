<?php namespace App\Services\Order\Checkout;

use App\Services\Order\OrderProtocol;

interface OrderCheckoutContract {


    public function checkout($order_id, $pay_type = OrderProtocol::BILLING_TYPE_OF_MONEY, $pay_channel = null);

    public function billingPaid($payment_no);

}
