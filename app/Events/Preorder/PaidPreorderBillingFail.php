<?php

namespace App\Events\Preorder;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PaidPreorderBillingFail extends Event
{
    use SerializesModels;
    /**
     * @var
     */
    public $billing;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($billing)
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
