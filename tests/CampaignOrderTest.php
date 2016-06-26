<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CampaignOrderTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_create_a_campaign_order()
    {
        $user_id = 1;

        $this->json('post', 'campaigns/orders',
            [
                'campaign' => 1,
                'channel' => 'wx_pub_qr'
            ],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );
//        $this->dumpResponse();

        $this->assertResponseStatus(201);

        $result = $this->getResponseData();

        $this->seeInDatabase('order_skus', ['order_id' => $result['data']['id'], 'pay_amount' => 0]);

        return $result['data'];
    }

    /** @test */
    public function it_can_create_campaign()
    {
        $user_id = 1;

        $data = [
            'name' => '优惠购',
            'cover_image' => 'adada',
            'desc' => '描述',
            'detail' => '详细描述',
            'start_time' => '2016-06-01',
            'end_time' => '2016-12-01',
            'active' => 1,
            'product_sku' => 2
        ];

        $this->json('post', 'admin/campaigns',
            $data,
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]);
        $result = $this->getResponseData();

        $this->assertResponseStatus(201);

        return $result['data']['id'];
    }

    /** @test */
    public function it_can_paid_a_campaign_order()
    {
        $user_id = 1;

        $order = $this->it_can_create_a_campaign_order();

        $this->json('POST', '/campaigns/orders/' . $order['order_no'] . '/checkout', ['channel' => 'wx_pub_qr'], ['Authorization' => 'Bearer ' . $this->getToken($user_id)]);

        $this->assertResponseOk();

        $charge = $this->getResponseData('data');

        //模拟付款
        $pay_url = "http://sissi.pingxx.com/notify.php?ch_id=" . $charge['id'];
        $this->getUrl($pay_url);

        $this->json('POST', '/campaigns/orders/' . $order['order_no'] . '/checkout', ['channel' => 'wx_pub_qr'], ['Authorization' => 'Bearer ' . $this->getToken($user_id)]);
        $charge = $this->getResponseData('data');


        $this->json('post', 'gateway/pingxx/paid',
            ['data' => ['object' => $charge]],
            []
        );

        $this->assertResponseStatus(202);

        $this->seeInDatabase('orders', ['order_no' => $order['order_no'], 'pay_status' => \App\Services\Order\OrderProtocol::PAID_STATUS_OF_PAID, 'status' => \App\Services\Order\OrderProtocol::STATUS_OF_SHIPPED]);
        $this->seeInDatabase('order_tickets', ['order_id' => $order['id'], 'status' => 'ok']);
        $this->seeInDatabase('order_special_campaign', ['order_id' => $order['id']]);

        return $order;
    }

    /** @test */
    public function it_can_get_order_ticket_detail()
    {
        $order = $this->it_can_paid_a_campaign_order();

        $ticket = \App\Models\OrderTicket::where('order_id', $order['id'])->first();

        $user_id = 1;

        $this->json('GET', 'campaigns/tickets/' . $ticket['ticket_no'],
            [],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->dumpResponse();
        $this->assertResponseOk();

    }
}
