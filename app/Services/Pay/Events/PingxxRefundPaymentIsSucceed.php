<?php

namespace App\Services\Pay\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PingxxRefundPaymentIsSucceed extends Event
{
    use SerializesModels;


    /**
     * @var
     */
    public $refund_payment;

    /**
     * Create a new event instance.
     *
     * @return void
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

  
}
