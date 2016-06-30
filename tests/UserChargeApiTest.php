<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserChargeApiTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_charge_wallet()
    {
        $user_id = 1;
        $amount = 100;
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

    /** @test */
    public function it_can_get_billings_lists()
    {
        $this->json('get', 'users/recharge', ['status' => 'paid'], $this->getAuthHeader());

        $this->assertResponseOk();
    }


}
