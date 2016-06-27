<?php

namespace App\Events\Preorder;

use App\Events\Event;
use App\Models\Billing\ChargeBilling;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ChargeBillingIsPaid extends Event
{
    use SerializesModels;
    /**
     * @var ChargeBilling
     */
    public $billing;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ChargeBilling $billing)
    {
        $this->billing = $billing;
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
