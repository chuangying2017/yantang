<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CartOrderApiTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function ite_can_create_a_cart_order()
    {
        $cart_ids = $this->it_can_add_a_sku_to_cart();
        $user_id = 1;
        $this->it_can_add_a_sku_to_cart();

        $address = \App\Models\Client\Address::create(['user_id' => $user_id, 'name' => 'troy']);

        $this->json('post', 'mall/orders/cart',
            [
                'cart_ids' => to_array($cart_ids),
                'address' => $address['id']
            ],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]);
        $result = $this->getResponseData();

        $this->assertResponseOk();

        $temp_order_id = $result['data']['temp_order_id'];


        //add address
        $this->json('put', 'mall/orders/cart/' . $temp_order_id,
            [
                'address' => $address['id']
            ],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]);
        $result = $this->getResponseData();

        $this->assertResponseOk();

        $this->json('post', 'mall/orders', ['temp_order_id' => $temp_order_id], ['Authorization' => 'Bearer ' . $this->getToken($user_id)]);

        $order = $this->getResponseData('data');

        $this->assertResponseStatus(201);

        return $order;
    }

    /** @test */
    public function it_can_get_get_mall_orders_lists()
    {
        $this->ite_can_create_a_cart_order();

        $user_id = 1;
        $this->json('get', 'mall/orders', [], ['Authorization' => 'Bearer ' . $this->getToken($user_id)]);

        $this->assertResponseStatus(200);

    }

    /** @test */
    public function it_can_get_get_mall_orders_detail()
    {
        $order = $this->ite_can_create_a_cart_order();

        $user_id = 1;
        $this->json('get', 'mall/orders/' . $order['order_no'],
            [],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]);

        $this->assertResponseStatus(200);

        $this->seeJsonStructure(['data' => ['skus', 'address']]);

//        $this->dumpResponse();

    }

    /** @test */
    public function it_can_add_a_sku_to_cart()
    {
        $user_id = 2;
        $this->json('POST', 'mall/cart', ['product_sku_id' => 2, 'quantity' => 2], ['Authorization' => 'Bearer ' . $this->getToken($user_id)]);

        $result = $this->getResponseData();
        $this->assertResponseStatus(201);
        return $result['data']['id'];
    }

    /** @test */
    public function it_can_create_a_mall_order_and_pay_by_pingxx()
    {
        $user_id = 1;
        $order = $this->ite_can_create_a_cart_order();
        $channel = 'wx_pub_qr';

        $this->json('post', 'mall/orders/' . $order['order_no'] . '/checkout', ['channel' => $channel], ['Authorization' => 'Bearer ' . $this->getToken($user_id)]);

        $result = $this->getResponseData();

        $pay_url = "http://sissi.pingxx.com/notify.php?ch_id=" . $result['data']['id'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $pay_url);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_NOBODY, TRUE); // remove body
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $head = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->json('post', 'mall/orders/' . $order['order_no'] . '/checkout', ['channel' => $channel], ['Authorization' => 'Bearer ' . $this->getToken($user_id)]);

        $charge = $this->getResponseData('data');

        $this->json('post', 'gateway/pingxx/paid',
            ['data' => ['object' => $charge]],
            []
        );

        $this->seeInDatabase('orders', ['order_no' => $order['order_no'], 'pay_status' => \App\Services\Order\OrderProtocol::PAID_STATUS_OF_PAID, 'status' => \App\Services\Order\OrderProtocol::STATUS_OF_PAID]);

        $this->assertResponseStatus(202);

        return $order;
    }

    /** @test */
    public function it_can_cancel_a_mall_order()
    {
        $order = $this->it_can_create_a_mall_order_and_pay_by_pingxx();

        $this->json('delete', 'mall/orders/' . $order['order_no'],
            [
                'memo' => '改变主意'
            ],
            $this->getAuthHeader());


        $this->assertResponseStatus(204);

        $this->seeInDatabase('orders', ['order_no' => $order['order_no'], 'refund_status' => \App\Services\Order\OrderProtocol::REFUND_STATUS_OF_REFUNDING]);
        $this->seeInDatabase('return_orders', ['order_id' => $order['id']]);

//        $this->setExpectedException(\Exception::class, '订单退款处理中,无法重复提交');

        $this->json('delete', 'mall/orders/' . $order['order_no'],
            [
                'memo' => '改变主意'
            ],
            $this->getAuthHeader());

        $this->assertResponseStatus(500);
    }

}
