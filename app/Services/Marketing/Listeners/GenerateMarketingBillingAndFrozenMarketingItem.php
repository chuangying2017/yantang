<?php

namespace App\Services\Marketing\Listeners;

use App\Services\Marketing\MarketingProtocol;
use App\Services\Marketing\MarketingRepository;
use App\Services\Orders\Event\OrderConfirm;
use App\Services\Orders\Payments\BillingRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateMarketingBillingAndFrozenMarketingItem {

    /**
     * Create the event listener.
     *
     * @return void
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
            BillingRepository::storeTicketBilling($order_id, $user_id, $ticket['discount_fee'], $ticket['id']);
            $ticket_id[] = $ticket['id'];
        }

        MarketingRepository::updateTicketsStatus($ticket_id, MarketingProtocol::STATUS_OF_FROZEN);
    }
}
