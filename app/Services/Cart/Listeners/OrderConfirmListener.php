<?php

namespace App\Services\Cart\Listeners;

use App\Services\Marketing\MarketingProtocol;
use App\Services\Marketing\MarketingRepository;
use App\Services\Orders\Event\OrderConfirm;
use App\Services\Orders\OrderProtocol;
use App\Services\Orders\Payments\PaymentRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderConfirmListener
{

    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderConfirm $event
     * @return void
     */
    public function handle(OrderConfirm $event)
    {
        $tickets = $event->tickets;
        $order_id = $event->order_id;
        $user_id = $event->user_id;

        $ticket_id = [];
        foreach ($tickets as $ticket) {
            PaymentRepository::storeBilling($order_id, $user_id, $ticket['discount_fee'], OrderProtocol::TYPE_OF_DISCOUNT, OrderProtocol::RESOURCE_OF_TICKET, $ticket['id']);
            $ticket_id[] = $ticket['id'];
        }

        MarketingRepository::updateTicketsStatus($ticket_id, MarketingProtocol::STATUS_OF_FROZEN);
    }
}
