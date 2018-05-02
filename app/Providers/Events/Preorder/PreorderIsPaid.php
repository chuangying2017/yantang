<?php

namespace App\Events\Preorder;

use App\Events\Event;
use App\Models\Subscribe\Preorder;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PreorderIsPaid extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param Preorder $preorder
     */
    public function __construct(Preorder $preorder)
    {
        $this->preorder = $preorder;
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
     * @var Preorder
     */
    public $preorder;
}
