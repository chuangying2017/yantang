<?php

namespace App\Events\Preorder;

use App\Events\Event;
use App\Models\Subscribe\PreorderAssign;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AssignIsAssigned extends Event {

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
    public function __construct(PreorderAssign $assign)
    {
        $assign->load('preorder');
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
