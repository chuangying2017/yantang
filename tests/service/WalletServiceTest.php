<?php

use App\Repositories\Client\Account\Wallet\EloquentWalletRepository;
use App\Services\Client\Account\WalletService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;


use App\Services\Billing\BillingContract;

class WalletServiceTest extends TestCase {

    use DatabaseTransactions;

    protected $billing_stub;
    protected $billing_id = 1;
    protected $billing_type;
    protected $payer = 1;

    /** @test */
    public function wallet_can_pay_billing()
    {
        \App\Models\Access\User\User::create(['id' => $this->payer]);
        $user_wallet = factory(\App\Models\Account\Wallet::class)->make(['user_id' => $this->payer, 'amount' => 1000]);

        \App\Models\Account\Wallet::create($user_wallet->toArray());

        $this->assertEquals(1000, $user_wallet['amount']);
        $this->assertEquals($this->payer, $user_wallet['user_id']);

        $this->billing_stub = $this->getMockBuilder(BillingContract::class)->getMock();

        $this->billing_stub->method('getID')->willReturn($this->billing_id);
        $this->billing_stub->method('getType')->willReturn($this->billing_type);
        $this->billing_stub->method('getPayer')->willReturn($this->payer);

        $this->assertEquals($this->payer, $this->billing_stub->getPayer());

        $billing_amount = 100;

        $this->billing_stub->method('getAmount')->willReturn($billing_amount);

        $wallet = $this->app->make(WalletService::class);
        $wallet->setPayer($this->payer);

        $this->assertEquals($this->payer, $wallet->getUserId());
        $this->assertInstanceOf(EloquentWalletRepository::class, $wallet->getAccount());
        $this->assertEquals($this->payer, $wallet->getAccount()->getUserId());

        $this->assertTrue($wallet->enough($billing_amount));

        
    }
}
