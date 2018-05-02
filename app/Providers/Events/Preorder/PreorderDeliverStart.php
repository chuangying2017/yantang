<?php

namespace App\Events\Preorder;

use App\Events\Event;
use App\Models\Subscribe\Preorder;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PreorderDeliverStart extends Event {

    use SerializesModels;

    /**
     * @var Preorder
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
