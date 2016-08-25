<?php

namespace App\Services\Pay\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PingxxPaymentIsFail extends Event {

    /**
     * @var
     */
    public $payment;

    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param $payment
     */
    public function __construct($payment)
    {
        $this->payment = $payment;
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
