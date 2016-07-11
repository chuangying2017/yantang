<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PreorderOrderApiTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_create_a_preorder_order()
    {
        $address = $this->it_can_create_a_subscribe_address();
        $data = [
            'skus' => [
                ['product_sku_id' => 2, 'quantity' => 60, 'per_day' => 2],
                ['product_sku_id' => 3, 'quantity' => 90, 'per_day' => 3],
            ],
            'address_id' => $address['id'],
            'station_id' => 1,
            'daytime' => 0,
            'weekday_type' => 'all',
            'start_time' => '2016-07-13',
            'channel' => 'wx_pub_qr'
        ];

        $this->json('post', 'subscribe/orders', $data, $this->getAuthHeader());

        $this->assertResponseStatus(201);

        $charge = $this->getResponseData('meta.charge');
        $order = $this->getResponseData('data');

        //模拟付款
        $pay_url = "http://sissi.pingxx.com/notify.php?ch_id=" . $charge['id'];
        $this->getUrl($pay_url);

        $this->json('POST', 'subscribe/orders/' . $order['order_no'] . '/checkout', ['channel' => 'wx_pub_qr'], ['Authorization' => 'Bearer ' . $this->getToken()]);
        $charge = $this->getResponseData('data');


        $this->json('post', 'gateway/pingxx/paid',
            ['data' => ['object' => $charge]],
            []
        );

        $this->assertResponseStatus(202);

        $this->seeInDatabase('preorders', ['order_id' => $order['id'], 'status' => \App\Services\Preorder\PreorderProtocol::ORDER_STATUS_OF_ASSIGNING, 'district_id' => 1]);

    }

    /** @test */
    public function it_can_create_a_subscribe_address()
    {
        $inside = [23.157195, 113.330319];
        $data = [
            'name' => 'asda',
            'phone' => '13232313123',
            'detail' => 'asdad',
            'longitude' => $inside[0],
            'latitude' => $inside[1],
            'district_id' => 1
        ];
        $this->json('post', 'subscribe/address',
            $data,
            $this->getAuthHeader()
        );

        $this->seeInDatabase('addresses', ['name' => 'asda', 'is_subscribe' => 1]);

        $this->assertResponseStatus(201);

        return $this->getResponseData('data');
    }
}
