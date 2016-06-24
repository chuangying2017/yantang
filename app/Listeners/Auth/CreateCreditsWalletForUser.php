<?php

namespace App\Listeners\Auth;

use App\Events\Auth\UserRegister;
use App\Repositories\Client\Account\Credits\CreditsRepositoryContract;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateCreditsWalletForUser {

    /**
     * @var CreditsRepositoryContract
     */
    private $creditsRepo;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(CreditsRepositoryContract $creditsRepo)
    {
        $this->creditsRepo = $creditsRepo;
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

        $this->creditsRepo->setUserId($user['id'])->createAccount();
    }
}
