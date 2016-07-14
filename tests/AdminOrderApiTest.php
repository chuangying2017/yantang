<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminOrderApiTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_get_paginate_mall_orders()
    {
        $user_id = 1;

        $this->json('get', 'admin/mall/orders',
//            ['keyword' => '131'],
            [],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->echoJson();

        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_deliver_a_mall_order()
    {
        $company = '顺丰';
        $post_no = '1231231';
        $data = [
            'company' => $company,
            'post_no' => $post_no
        ];
        $order_no = '102160701068101251955';

        $this->json('put', 'admin/mall/orders/' . $order_no, $data, $this->getAuthHeader());

        $this->assertResponseStatus(200);

        $this->echoJson();

        $this->seeInDatabase('orders', ['order_no' => $order_no, 'status' => \App\Services\Order\OrderProtocol::STATUS_OF_SHIPPING]);
    }

    /** @test */
    public function it_can_get_a_mall_order_detail()
    {
        $order_no = '102160701068101251955';

        $this->json('get', 'admin/mall/orders/' . $order_no, [], $this->getAuthHeader());

        $this->echoJson();

        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_get_paginate_special_orders()
    {
        $user_id = 1;

        $this->json('get', 'admin/special/orders',
            ['keyword' => '104160622827037798579'],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->dumpResponse();

        $this->assertResponseOk();

    }
}
