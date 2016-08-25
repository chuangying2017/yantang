<?php

namespace App\Services\Pay\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PingxxRefundPaymentIsDone extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param $refund_payment
     */
    public function __construct($refund_payment)
    {
        $this->refund_payment = $refund_payment;
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

    /**
     * @var
     */
    public $refund_payment;
}
