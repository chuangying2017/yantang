<?php

namespace App\Services\Pay\Events;

use App\Events\Event;
use App\Models\PingxxPayment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PingxxPaymentIsPaid extends Event {

    use SerializesModels;
    /**
     * @var PingxxPayment
     */
    public $payment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PingxxPayment $payment)
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
