<?php namespace App\Services\Marketing\Items\Coupon;

use App\Services\Marketing\MarketingItemUsing;
use App\Services\Marketing\MarketingProtocol;
use App\Services\Marketing\MarketingRepository;

class UseCoupon extends MarketingItemUsing {

    public function __construct()
    {
        $this->setResourceType(MarketingProtocol::TYPE_OF_COUPON);
    }

    public function used($ticket_id, $user_id)
    {

    }

    public function discountFee($ticket_id, $pay_amount)
    {
        $ticket = self::show($ticket_id);
        $resource = $ticket['resource'];

        return self::calculateDiscountFee($resource['type'], $resource['content'], $pay_amount);
    }

    /**
     * $order_detail = [
     * 'products'     => [
     * [
     * 'product_sku_id' => 1,
     * 'quantity'       => 2,
     * 'price'          => 1000,
     * 'category_id'    => 1
     * ]
     * ],
     * 'total_amount' => 10000,
     * 'discount_fee' => 100
     * ];
     * @param $user_id
     * @param $order_detail
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function usableList($user_id, $order_detail)
    {
        $tickets = isset($order_detail['marketing']['coupons'])
            ? $order_detail['marketing']['coupons']
            : $this->getTickets($user_id, $order_detail);

        foreach ($tickets as $key => $ticket) {
            $ticket->can_use = $this->filter($ticket, $order_detail);
            if ( ! $ticket->can_use) {
                $ticket->reason = $this->getMessage();
            }
        }

        return $tickets;
    }

    public function getTickets($user_id, $order_detail)
    {
        return array_get($order_detail, 'marketing.coupons', $this->lists($user_id));
    }


}
