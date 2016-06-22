<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CampaignOrderTest extends TestCase {

    /** @test */
    public function it_can_create_a_campaign_order()
    {
        $product_skus = [
            [
                'quantity' => 1,
                'product_sku_id' => 2
            ]
        ];
        $channel = 'wx_pub_qr';
        $user_id = 1;

        $this->json('post', 'campaigns/orders',
            [
                'product_skus' => $product_skus,
                'channel' => $channel,
                'campaign' => $this->it_can_create_campaign()
            ],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]);

        $this->assertResponseOk();

        $result = $this->getResponseData();

        $this->visit("http://sissi.pingxx.com/notify.php?ch_id=" . $result['data']['id']);

        $this->see('success');

    }

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
            'active' => 1
        ];

        $this->json('post', 'admin/campaigns',
            $data,
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]);
        $result = $this->getResponseData();

        $this->assertResponseStatus(201);

        return $result['data']['id'];
    }
}
