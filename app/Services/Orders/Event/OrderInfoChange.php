<?php

namespace App\Services\Orders\Event;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderInfoChange extends Event
{
    use SerializesModels;
    /**
     * @var
     */
    public $order_info;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($order_info)
    {
        $this->order_info = $order_info;
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
