<?php

namespace App\Events\Frontend\Auth;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserRegister extends Event
{
    use SerializesModels;

    public $user;
    /**
     * @var
     */
    public $register_data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $register_data)
    {
        $this->user = $user;
        $this->register_data = $register_data;
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
