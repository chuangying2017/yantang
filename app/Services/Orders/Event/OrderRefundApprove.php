<?php

namespace App\Services\Orders\Event;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderRefundApprove extends Event
{
    use SerializesModels;
    /**
     * @var
     */
    public $refund_order;

    /**
     * Create a new event instance.
     *
     * @param $refund_order
     */
    public function __construct($refund_order)
    {
        $this->refund_order = $refund_order;
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
