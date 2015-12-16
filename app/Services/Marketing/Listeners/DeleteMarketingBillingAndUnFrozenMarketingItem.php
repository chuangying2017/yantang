<?php

namespace App\Services\Marketing\Listeners;

use App\Services\Marketing\MarketingProtocol;
use App\Services\Marketing\MarketingRepository;
use App\Services\Orders\Event\OrderCancel;
use App\Services\Orders\OrderProtocol;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteMarketingBillingAndUnFrozenMarketingItem {

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
     * @param  OrderCancel $event
     * @return void
     */
    public function handle(OrderCancel $event)
    {
        $billings = $event->billings;

        $ticket_id = [];
        foreach ($billings as $billing) {
            if ($billing['resource_type'] == OrderProtocol::RESOURCE_OF_TICKET) {
                $ticket_id[] = $billing['resource_id'];
                $billing->delete();
            }
        }
        MarketingRepository::updateTicketsStatus($ticket_id, MarketingProtocol::STATUS_OF_PENDING);
    }
}
