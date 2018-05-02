<?php

namespace App\Services\Comments\Event;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CommentIsCreated extends Event
{
    use SerializesModels;
    /**
     * @var
     */
    public $comment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($comment)
    {
        $this->comment = $comment;
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
