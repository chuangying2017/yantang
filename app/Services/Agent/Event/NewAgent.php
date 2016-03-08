<?php

namespace App\Services\Agent\Event;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewAgent extends Event
{
    use SerializesModels;
    /**
     * @var
     */
    public $agent;
    /**
     * @var
     */
    public $agent_info;

    /**
     * Create a new event instance.
     *
     * @param $agent
     * @param $agent_info
     */
    public function __construct($agent, $agent_info)
    {
        $this->agent = $agent;
        $this->agent_info = $agent_info;
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
