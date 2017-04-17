<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderIsPaid;
use App\Services\Order\OrderProtocol;
use App\Services\OrderTicket\OrderTicketManageContract;
use App\Services\Order\OrderManageContract;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateOrderTicketForCampaignOrder {

    /**
     * @var OrderTicketManageContract
     */
    private $orderTicketManage;

    /**
     * @var OrderRepositoryContract
     */
    private $orderManage;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(OrderTicketManageContract $orderTicketManage,
       OrderManageContract $orderManage)
    {
        $this->orderTicketManage = $orderTicketManage;
        $this->orderManage = $orderManage;
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
        else if( $order['order_type'] == OrderProtocol::ORDER_TYPE_OF_COLLECT) {
            $this->orderManage->orderDone($order['id']);
        }
    }
}
