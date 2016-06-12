<?php namespace App\Services\Order\Checkout;
interface OrderCheckoutContract {

    public function checkout($order_id, $pay_type, $pay_channel = null);

    public function billingPaid($payment_no);

}
