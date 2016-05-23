<?php

use App\Services\Billing\BillingContract;
use App\Services\Pay\Pingxx\PingxxPayService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PaymentServiceTest extends TestCase {

    use DatabaseTransactions;


    protected $billing_stub;
    protected $billing_id = 1;
    protected $billing_type = \App\Services\Billing\BillingProtocol::BILLING_TYPE_OF_ORDER_BILLING;
    protected $payer = null;
    protected $billing_amount = 100;

    /** @test */
    public function it_can_pay_a_billing()
    {
        $this->setBilling();

        $pay = $this->createApplication()->make(PingxxPayService::class);
        $pay->setChannel(\App\Services\Pay\Pingxx\PingxxProtocol::PINGXX_WAP_CHANNEL_ALIPAY);
        $charge = $pay->pay($this->billing_stub);
        $this->assertInstanceOf(\Pingpp\Charge::class, $charge);
    }


    protected function setBilling()
    {
        $this->billing_stub = $this->getMockBuilder(BillingContract::class)->getMock();

        $this->billing_stub->method('getID')->willReturn($this->billing_id);
        $this->billing_stub->method('getType')->willReturn($this->billing_type);
        $this->billing_stub->method('getAmount')->willReturn($this->billing_amount);
    }
}
