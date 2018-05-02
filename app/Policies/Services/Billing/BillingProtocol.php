<?php namespace App\Services\Billing;

use App\Models\Billing\ChargeBilling;
use App\Models\Billing\OrderBilling;
use App\Models\Billing\PreorderBilling;
use App\Models\Order\OrderPromotion;

class BillingProtocol {

    const BILLING_TYPE_OF_ORDER_BILLING = OrderBilling::class;
    const BILLING_TYPE_OF_RECHARGE_BILLING = ChargeBilling::class;
    const BILLING_TYPE_OF_PREORDER_ORDER_BILLING = PreorderBilling::class;
    
    const BILLING_TYPE_OF_ORDER_PROMOTION = OrderPromotion::class;

    const STATUS_OF_UNPAID = 'unpaid';
    const STATUS_OF_PAID = 'paid';

    //Billing
    const BILLING_TYPE_OF_MONEY = 'money';
    const BILLING_TYPE_OF_CREDITS = 'credits';


    const BILLING_PER_PAGE = 20;
    const CHARGE_BILLING_PER_PAGE = 20;


}
