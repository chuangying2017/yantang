<?php

namespace App\Listeners\Preorder;

use App\Events\Preorder\ChargeBillingIsPaid;
use App\Services\Billing\ChargeBillingService;
use App\Services\Client\Account\WalletService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RechargeWallet {

    /**
     * @var ChargeBillingService
     */
    private $billingService;
    /**
     * @var WalletService
     */
    private $walletService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ChargeBillingService $billingService, WalletService $walletService)
    {
        $this->billingService = $billingService;
        $this->walletService = $walletService;
    }

    /**
     * Handle the event.
     *
     * @param  ChargeBillingIsPaid $event
     * @return void
     */
    public function handle(ChargeBillingIsPaid $event)
    {
        $billing = $event->billing;
        $this->walletService->recharge($this->billingService->setID($billing));
    }
}
