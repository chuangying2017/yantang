<?php

namespace App\Listeners\Auth;

use App\Events\Auth\UserRegister;
use App\Repositories\Client\Account\Credits\CreditsRepositoryContract;
use App\Repositories\Client\Account\Wallet\WalletRepositoryContract;
use App\Repositories\Client\ClientRepositoryContract;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateClientForUser {

    /**
     * @var ClientRepositoryContract
     */
    private $client;
    /**
     * @var WalletRepositoryContract
     */
    private $wallet;
    /**
     * @var CreditsRepositoryContract
     */
    private $credits;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ClientRepositoryContract $client, WalletRepositoryContract $wallet, CreditsRepositoryContract $credits)
    {
        $this->client = $client;
        $this->wallet = $wallet;
        $this->credits = $credits;
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
        $this->wallet->setUserId($user['id'])->createAccount();
        $this->credits->setUserId($user['id'])->createAccount();
    }
}
