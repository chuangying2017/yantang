<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PreorderOrderApiTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_create_a_preorder_temp_order()
    {
//        $address = $this->it_can_create_a_subscribe_address();
        $data = [
            'skus' => [
                ['product_sku_id' => 2, 'quantity' => 60, 'per_day' => 2],
                ['product_sku_id' => 3, 'quantity' => 90, 'per_day' => 3],
            ],
            'address_id' => 127,
            'station_id' => 1,
            'daytime' => 0,
            'weekday_type' => 'all',
            'start_time' => '2016-08-28',
            'channel' => 'wx_pub_qr'
        ];

        $this->json('post', 'subscribe/orders', $data, $this->getAuthHeader());

        $this->assertResponseStatus(200);

        $this->echoJson();

        return $this->getResponseData('data.temp_order_id');
    }

    /** @test */
    public function it_can_create_a_preorder_order()
    {
        $temp_order_id = $this->it_can_create_a_preorder_temp_order();
        $data = [
            'channel' => 'wx_pub_qr'
        ];

        $this->json('put', 'subscribe/orders/' . $temp_order_id . '/confirm', $data, $this->getAuthHeader());

//        $this->echoJson();

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

        $this->seeInDatabase('orders', ['id' => $order['id'], 'status' => \App\Services\Order\OrderProtocol::STATUS_OF_PAID]);

        $this->seeInDatabase('preorders', ['order_id' => $order['id'], 'status' => \App\Services\Preorder\PreorderProtocol::ORDER_STATUS_OF_ASSIGNING]);

        return $order;
    }

    /** @test */
    public function it_can_create_a_subscribe_address()
    {
//        $this->it_can_create_another_station();


        $inside = [23.099075, 113.291048];
//        $out_side = [23.151668243459564, 113.32917949894649];

        $data = [
            'name' => 'asda',
            'phone' => 1,
            'street' => '街道',
            'detail' => 'adasd',
            'longitude' => $inside[0],
            'latitude' => $inside[1],
            'district_id' => 440103
        ];
        $this->json('post', 'subscribe/address',
            $data,
            $this->getAuthHeader()
        );

        $this->seeInDatabase('addresses', ['name' => 'asda', 'is_subscribe' => 1]);

        $this->assertResponseStatus(201);

        return $this->getResponseData('data');
    }


    /** @test */
    public function it_can_create_a_order_and_refund()
    {
        $order = $this->it_can_create_a_preorder_order();

        $this->json('delete', 'subscribe/orders/' . $order['order_no'],
            [
                'memo' => '后悔'
            ], $this->getAuthHeader());

        $this->seeInDatabase('preorders', ['order_id' => $order['id'], 'status' => \App\Services\Preorder\PreorderProtocol::ORDER_STATUS_OF_CANCEL]);
        $this->assertResponseStatus(204);
    }

}
