<?php

namespace App\Listeners\Frontend\Auth;

use App\Events\Frontend\Auth\UserRegister;
use App\Services\Client\ClientService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateClientForUser {

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserRegister $event
     * @return void
     */
    public function handle(UserRegister $event)
    {
        $user = $event->user;

        ClientService::create($user);
    }
}
