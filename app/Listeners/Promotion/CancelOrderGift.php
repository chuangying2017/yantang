<?php

namespace App\Listeners\Promotion;

use App\Events\Order\OrderIsCancel;
use App\Repositories\Promotion\TicketRepositoryContract;
use App\Services\Promotion\PromotionProtocol;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CancelOrderGift {

    /**
     * @var TicketRepositoryContract
     */
    private $ticketRepo;

    /**
     * Create the event listener.
     *
     * @param TicketRepositoryContract $ticketRepo
     */
    public function __construct(TicketRepositoryContract $ticketRepo)
    {
        $this->ticketRepo = $ticketRepo;
    }

    /**
     * Handle the event.
     *
     * @param  OrderIsCancel $event
     * @return void
     */
    public function handle(OrderIsCancel $event)
    {
        $order = $event->order;
        $tickets = $this->ticketRepo->getTicketBySource(PromotionProtocol::TICKET_RESOURCE_OF_ORDER, $order['id']);
        if (count($tickets)) {
            foreach ($tickets as $ticket) {
                $this->ticketRepo->updateAsCancel($ticket);
            }
        }
    }


}
