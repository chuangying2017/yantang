<?php

use App\Services\Preorder\PreorderSettleService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PreorderSettleTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_settle_preorders()
    {
        $order_id = 2;
//        app()->make(PreorderSettleService::class)->settle();
//
//        $this->seeInDatabase('preorders', ['id' => $order_id, 'charge_status' => \App\Services\Preorder\PreorderProtocol::CHARGE_STATUS_OF_NOT_ENOUGH]);

        $this->it_can_charge_wallet();

        app()->make(PreorderSettleService::class)->settle();

        $this->seeInDatabase('preorder_billings', [
            'preorder_id' => $order_id,
            'user_id' => 1,
            'staff_id' => 1,
            'station_id' => 1,
            'amount' => 81600
        ]);

        $this->seeInDatabase('wallet', [
            'user_id' => 1,
            'amount' => 100000 - 81600
        ]);

        $this->seeInDatabase('wallet_records', ['resource_type' => \App\Models\Billing\PreorderBilling::class, 'amount' => 81600]);
    }


    /** @test */
    public function it_can_charge_wallet()
    {
        $user_id = 1;
        $amount = 100000;
        $this->json('post', 'users/recharge',
            [
                'amount' => $amount,
                'channel' => 'wx_pub_qr'
            ],
            $this->getAuthHeader($user_id)
        );

        $this->assertResponseStatus(201);

        $charge = $this->getResponseData('meta.charge');

        //模拟付款
        $pay_url = "http://sissi.pingxx.com/notify.php?ch_id=" . $charge['id'];
        $this->getUrl($pay_url);

        $charge = app()->make(\App\Repositories\Pay\Pingxx\PingxxPaymentRepository::class)->getCharge($charge['id']);

        $this->json('post', 'gateway/pingxx/paid',
            ['data' => ['object' => $charge]],
            []
        );

        $this->assertResponseStatus(202);

        $this->seeInDatabase('wallet', ['user_id' => $user_id, 'amount' => $amount]);
        $this->seeInDatabase('preorders', ['user_id' => $user_id, 'charge_status' => 1]);
    }
}
