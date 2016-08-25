<?php

namespace App\Events\Store;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class StoreStatementConfirm extends Event
{
    use SerializesModels;
    /**
     * @var
     */
    public $statement;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($statement)
    {
        $this->statement = $statement;
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
