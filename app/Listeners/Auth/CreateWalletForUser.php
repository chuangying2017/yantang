<?php

namespace App\Listeners\Auth;

use App\Events\Auth\UserRegister;
use App\Repositories\Client\Account\Wallet\WalletRepositoryContract;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateWalletForUser {

    /**
     * @var WalletRepositoryContract
     */
    private $walletRepo;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(WalletRepositoryContract $walletRepo)
    {
        $this->walletRepo = $walletRepo;
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
        $this->walletRepo->setUserId($user['id'])->createAccount();
    }
}
