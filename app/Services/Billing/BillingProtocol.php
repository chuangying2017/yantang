<?php namespace App\Services\Billing;

use App\Models\Billing\RechargeBilling;
use App\Models\Billing\OrderBilling;

class BillingProtocol
{

    const BILLING_TYPE_OF_ORDER_BILLING = OrderBilling::class;
    const BILLING_TYPE_OF_RECHARGE_BILLING = RechargeBilling::class;
    const BILLING_CHANNEL_OF_PREORDER_BILLING = 'wx';

}
