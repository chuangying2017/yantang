<?php

namespace App\Listeners\Promotion;

use App\Models\ResidencePromoOrder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetResidencePromotionOrder {

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

        $order->residence_id = $residence_id;
        try{

        }
        catch(\Exception $e){
            \Log::debug('Cannot record promotion order, maybe there already got one.', $order);
        }
    }


}
