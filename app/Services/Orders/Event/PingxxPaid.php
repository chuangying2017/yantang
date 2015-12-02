<?php

namespace App\Services\Orders\Event;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PingxxPaid extends Event
{
    use SerializesModels;
    /**
     * @var
     */
    public $billing_id;
    /**
     * @var
     */
    public $order_id;
    /**
     * @var
     */
    public $pingxx_payment_id;

    /**
     * Create a new event instance.
     *
     * @param $order_id
     * @param $billing_id
     */
    public function __construct($order_id, $billing_id, $pingxx_payment_id)
    {
        //
        $this->billing_id = $billing_id;
        $this->order_id = $order_id;
        $this->pingxx_payment_id = $pingxx_payment_id;
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
