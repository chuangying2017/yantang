<?php

namespace App\Services\Agent\Event;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewAgentOrder extends Event
{
    use SerializesModels;
    /**
     * @var
     */
    public $agent_order;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($agent_order)
    {
        //
        $this->agent_order = $agent_order;
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
