<?php

namespace App\Events\Order;

use App\Events\Event;
use App\Services\Order\Generator\TempOrder;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderIsCreated extends Event
{
    use SerializesModels;
    /**
     * @var TempOrder
     */
    public $temp_order;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TempOrder $temp_order)
    {
        $this->temp_order = $temp_order;
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
