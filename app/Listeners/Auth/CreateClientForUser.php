<?php

namespace App\Listeners\Auth;

use App\Events\Auth\UserRegister;
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
        $register_data = $event->register_data;

        ClientService::create($user, $register_data);
    }
}
