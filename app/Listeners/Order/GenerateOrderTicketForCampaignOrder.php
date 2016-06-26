<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderIsPaid;
use App\Services\Order\OrderProtocol;
use App\Services\OrderTicket\OrderTicketManageContract;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateOrderTicketForCampaignOrder {

    /**
     * @var OrderTicketManageContract
     */
    private $orderTicketManage;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(OrderTicketManageContract $orderTicketManage)
    {
        $this->orderTicketManage = $orderTicketManage;
    }

    /**
     * Handle the event.
     *
     * @param  OrderIsPaid $event
     * @return void
     */
    public function handle(OrderIsPaid $event)
    {
        $order = $event->order;
        if ($order['order_type'] == OrderProtocol::ORDER_TYPE_OF_CAMPAIGN) {
            $this->orderTicketManage->createTicket($order);
        }
    }
}
