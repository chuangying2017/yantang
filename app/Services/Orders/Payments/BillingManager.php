<?php namespace App\Services\Orders\Payments;

use App\Services\Marketing\MarketingItemUsing;

use App\Services\Marketing\TicketService;
use App\Services\Orders\Exceptions\WrongStatus;
use App\Services\Orders\OrderProtocol;
use Pingpp\Charge;

class BillingManager {


    public static function mainBillingIsPaid($order_id, $pingxx_payment_id, $pay_type)
    {
        try {
            $billing = BillingRepository::getMainBilling($order_id);
            if ($billing['status'] == OrderProtocol::STATUS_OF_UNPAID) {
                BillingRepository::mainBillingIsPaid($billing['id'], $pingxx_payment_id, $pay_type);

                event(new \App\Services\Orders\Event\OrderIsPaid($order_id));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function checkMainBillingIsPaid($order_id)
    {
        $billing = BillingRepository::getMainBilling($order_id);
        if ($billing['status'] == OrderProtocol::STATUS_OF_PAID) {
            return true;
        }

        return false;
    }

    /**
     * 标记优惠账单已支付
     * @param $order_id
     */
    public static function paidMarketingBilling($order_id)
    {
        $billings = BillingRepository::getMarketingBilling($order_id);

        if ( ! count($billings)) {
            return;
        }

        $billings_id = [];
        $tickets_id = [];
        foreach ($billings as $billing) {
            $billings_id[] = $billing['id'];
            if ($billing['resource_type'] == OrderProtocol::RESOURCE_OF_TICKET) {
                $tickets_id[] = $billing['resource_id'];
            }
        }

        if (count($billings_id)) {
            BillingRepository::billingPaid($billings_id);
        }

        if (count($tickets_id)) {
            TicketService::used($tickets_id);
        }
    }


    public static function storeOrderMainBilling($order_id, $user_id, $pay_amount)
    {
        return BillingRepository::getMainBilling($order_id) ?: BillingRepository::storeMainBilling($order_id, $user_id, $pay_amount);
    }


}
