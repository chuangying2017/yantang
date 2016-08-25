<?php

namespace App\Events\Preorder;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PreorderSkusOut extends Event
{
    use SerializesModels;
    /**
     * @var
     */
    public $preorder;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($preorder)
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
}
