<?php

use App\Services\Order\OrderProtocol;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PreorderOrderApiTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_create_a_preorder_temp_order()
    {
        $address = $this->it_can_create_a_subscribe_address();
        $data = [
            'skus' => [
                ['product_sku_id' => 226, 'quantity' => 30, 'per_day' => 2],
            ],
            'address_id' => $address['id'],
            'station_id' => 1,
            'daytime' => 0,
            'weekday_type' => 'all',
            'start_time' => \Carbon\Carbon::today()->addDays(2)->toDateString(),
            'channel' => 'wx_pub_qr'
        ];

        $this->json('post', 'subscribe/orders', $data, $this->getAuthHeader());

        $this->assertResponseStatus(200);

        $this->dump();

        return $this->getResponseData('data.temp_order_id');
    }

    /** @test */
    public function it_can_create_a_preorder_temp_order_and_use_a_coupon()
    {
        $temp_order_id = $this->it_can_create_a_preorder_temp_order();

        $this->json('put', 'subscribe/orders/' . $temp_order_id, [
            'ticket' => 47570
        ], $this->getAuthHeader());

//        $this->json('put', 'subscribe/orders/' . $temp_order_id, [
//            'ticket' => 51
//        ], $this->getAuthHeader());

//        $this->dump();
        return $this->getResponseData('data.temp_order_id');

    }

    /** @test */
    public function it_can_create_a_preorder_order()
    {
        $temp_order_id = $this->it_can_create_a_preorder_temp_order_and_use_a_coupon();
        $data = [
            'channel' => 'wx_pub_qr'
        ];

        $this->json('put', 'subscribe/orders/' . $temp_order_id . '/confirm', $data, $this->getAuthHeader());

//        $this->echoJson();

        $this->assertResponseStatus(201);
        $charge = $this->getResponseData('meta.charge');
        $order = $this->getResponseData('data');

        //无需付款
        $this->assertEquals($charge, \App\Services\Order\OrderProtocol::ORDER_IS_PAID);
        return $order;

        //模拟付款
        $pay_url = "http://sissi.pingxx.com/notify.php?ch_id=" . $charge['id'];
        $this->getUrl($pay_url);

        $this->json('POST', 'subscribe/orders/' . $order['order_no'] . '/checkout', ['channel' => 'wx_pub_qr'], ['Authorization' => 'Bearer ' . $this->getToken()]);

//        $charge = $this->getResponseData('data');
//
//        $this->json('post', 'gateway/pingxx/paid',
//            ['data' => ['object' => $charge]],
//            []
//        );
//
//
//        $this->assertResponseStatus(202);

        $this->seeInDatabase('orders', ['id' => $order['id'], 'status' => \App\Services\Order\OrderProtocol::STATUS_OF_PAID]);

        $this->seeInDatabase('preorders', ['order_id' => $order['id'], 'status' => \App\Services\Preorder\PreorderProtocol::ORDER_STATUS_OF_ASSIGNING]);

        $this->seeInDatabase('tickets', [
                'user_id' => 1,
                'promotion_id' => 34,
                'source_type' => \App\Services\Promotion\PromotionProtocol::TICKET_RESOURCE_OF_ORDER,
                'source_id' => $order['id']]
        );

//        $this->seeInDatabase('red_records', [
//            'user_id' => 1,
//            'rule_id' => 1,
//            'resource_type' => \App\Repositories\RedEnvelope\RedEnvelopeProtocol::TYPE_OF_SUBSCRIBE_ORDER,
//            'resource_id' => $order['id']]);

        $this->seeInDatabase('order_marks', [
            'order_id' => $order['id'],
            'mark_type' => \App\Services\Order\OrderProtocol::ORDER_MARK_TYPE_OF_FIRST,
            'mark_content' => \App\Services\Order\OrderProtocol::ORDER_MARK_CONTENT_OF_FIRST
        ]);

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
    public function it_can_create_a_order_and_cancel_and_refund()
    {
        $order = $this->it_can_create_a_preorder_order();

//        $order = \App\Models\Order\Order::query()->find(1720);

        $this->json('delete', 'subscribe/orders/' . $order['order_no'],
            [
                'memo' => '后悔'
            ], $this->getAuthHeader());

        $this->seeInDatabase('orders', ['id' => $order['id'], 'status' => OrderProtocol::STATUS_OF_CANCEL]);
        $this->seeInDatabase('preorders', ['order_id' => $order['id'], 'status' => \App\Services\Preorder\PreorderProtocol::ORDER_STATUS_OF_CANCEL]);
        $this->assertResponseStatus(204);
    }

    /** @test */
    public function it_can_show_a_preorder_detail()
    {
        $preorder_id = 1631;

        $this->json('get', 'subscribe/preorders/' . $preorder_id, [], $this->getAuthHeader(3352));

        $this->dump();
    }

}
