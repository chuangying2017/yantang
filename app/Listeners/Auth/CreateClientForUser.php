<?php

namespace App\Listeners\Auth;

use App\Events\Auth\UserRegister;
use App\Repositories\Client\ClientRepositoryContract;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateClientForUser {

    /**
     * @var ClientRepositoryContract
     */
    private $client;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ClientRepositoryContract $client)
    {
        $this->client = $client;
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

        $this->client->createClient($user['id'], $register_data);
    }
}
