<?php

use App\Repositories\Client\Account\Wallet\EloquentWalletRepository;
use App\Services\Client\Account\WalletService;
use App\Services\Pay\Exception\NotEnoughException;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;


use App\Services\Billing\BillingContract;

class WalletServiceTest extends TestCase {

    use DatabaseTransactions;

    protected $billing_stub;
    protected $billing_id = 1;
    protected $billing_type = \App\Services\Billing\BillingProtocol::BILLING_TYPE_OF_ORDER_BILLING;
    protected $payer = null;
    protected $wallet_amount = 1000;
    protected $billing_amount = 100;
    protected $wallet_service;

    protected function setUp()
    {
        parent::setUp();
        $this->setWallet();
    }

    /** @test */
    public function billing_and_wallet_init_success()
    {
        $this->setBilling();
        $this->assertEquals($this->wallet_amount, $this->payer->wallet->amount);
        $this->assertEquals($this->payer->id, $this->billing_stub->getPayer());
        $this->assertEquals($this->payer->id, $this->wallet_service->getPayer());
        $this->assertInstanceOf(EloquentWalletRepository::class, $this->wallet_service->getAccount());
        $this->assertEquals($this->payer->id, $this->wallet_service->getAccount()->getUserId());
    }


    /** @test */
    public function wallet_can_pay_billing()
    {
        $this->setBilling();

        $this->assertTrue($this->wallet_service->enough($this->billing_stub->getAmount()));

        $this->wallet_service->pay($this->billing_stub);

        $current_wallet_account = \App\Models\Client\Account\Wallet::find($this->payer->id);
        $this->assertEquals($this->wallet_amount - $this->billing_amount, $current_wallet_account['amount']);
        $this->seeInDatabase('wallet_records', [
            'user_id' => $this->payer->id,
            'amount' => $this->billing_amount,
            'income' => \App\Services\Client\Account\AccountProtocol::ACCOUNT_OUTCOME,
            'resource_type' => $this->billing_type,
            'resource_id' => $this->billing_id,
            'type' => \App\Services\Client\Account\AccountProtocol::ACCOUNT_TYPE_USE
        ]);


    }

    /** @test */
    public function can_not_pay_same_billing_twice()
    {
        $this->setBilling();

        $this->assertTrue($this->wallet_service->enough($this->billing_stub->getAmount()));

        $this->wallet_service->pay($this->billing_stub);

        $this->setExpectedException(\Exception::class, '重复处理账单');
        $this->wallet_service->pay($this->billing_stub);
    }

    /** @test */
    public function can_pay_if_billing_not_enough()
    {
        $this->billing_amount = $this->wallet_amount + 1;
        $this->setBilling();

        $this->assertFalse($this->wallet_service->enough($this->billing_stub->getAmount()));

        $this->setExpectedException(NotEnoughException::class);
        $this->wallet_service->pay($this->billing_stub);

    }

    /** @test */
    public function wallet_can_be_recharge()
    {
        $this->billing_type = \App\Services\Billing\BillingProtocol::BILLING_TYPE_OF_RECHARGE_BILLING;
        $this->billing_amount = 100;
        $this->setBilling();

        $record = $this->wallet_service->recharge($this->billing_stub);

        $current_wallet_account = \App\Models\Client\Account\Wallet::find($this->payer->id);
        $this->assertEquals($this->wallet_amount + $this->billing_amount, $current_wallet_account['amount']);
        $this->seeInDatabase('wallet_records', [
            'user_id' => $this->payer->id,
            'amount' => $this->billing_amount,
            'income' => \App\Services\Client\Account\AccountProtocol::ACCOUNT_INCOME,
            'resource_type' => $this->billing_type,
            'resource_id' => $this->billing_id,
            'type' => \App\Services\Client\Account\AccountProtocol::ACCOUNT_TYPE_RECHARGE,
        ]);
    }

    protected function setBilling()
    {
        $this->billing_stub = $this->getMockBuilder(BillingContract::class)->getMock();

        $this->billing_stub->method('getID')->willReturn($this->billing_id);
        $this->billing_stub->method('getType')->willReturn($this->billing_type);
        $this->billing_stub->method('getPayer')->willReturn($this->payer->id);
        $this->billing_stub->method('getAmount')->willReturn($this->billing_amount);
    }

    protected function setWallet()
    {
        $user = \App\Models\Access\User\User::create();
        $user->wallet()->create(['amount' => $this->wallet_amount]);
        $this->payer = $user;
        $this->wallet_service = $this->app->make(WalletService::class);
        $this->wallet_service->setPayer($this->payer->id);
    }

}
