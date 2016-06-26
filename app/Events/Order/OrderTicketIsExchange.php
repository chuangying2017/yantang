<?php

namespace App\Events\Order;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderTicketIsExchange extends Event
{
    use SerializesModels;
    /**
     * @var
     */
    public $order_ticket;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($order_ticket)
    {
        //
        $this->order_ticket = $order_ticket;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
