<?php

namespace App\Events\Preorder;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AssignIsAssigned extends Event
{
    use SerializesModels;
    /**
     * @var
     */
    public $assign;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($assign)
    {
        $this->assign = $assign;
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
