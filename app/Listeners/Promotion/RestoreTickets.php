<?php

namespace App\Listeners\Promotion;

use App\Events\Order\OrderIsCancel;
use App\Repositories\Promotion\TicketRepositoryContract;
use App\Services\Promotion\PromotionProtocol;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RestoreTickets {

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
        $order->load('promotions');
        $promotions = $order['promotions'];

        if (count($promotions)) {
            foreach ($promotions as $promotion) {
                $this->ticketRepo->rollback($promotion['ticket_id']);
            }
        }
    }


}
