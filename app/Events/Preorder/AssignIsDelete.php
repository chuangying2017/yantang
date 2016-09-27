<?php

namespace App\Events\Preorder;

use App\Events\Event;
use App\Models\Subscribe\PreorderAssign;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AssignIsDelete extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param PreorderAssign $assign
     */
    public function __construct(PreorderAssign $assign)
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

    /**
     * @var PreorderAssign
     */
    public $assign;
}
