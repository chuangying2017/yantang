<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CartOrderApiTest extends TestCase {

//    use DatabaseTransactions;

    /** @test */
    public function ite_can_create_a_cart_order()
    {
        $cart_ids = $this->it_can_add_a_sku_to_cart();
        $user_id = 1;
        $this->it_can_add_a_sku_to_cart();

        $address = \App\Models\Client\Address::create(['user_id' => $user_id]);

        $this->json('post', 'mall/orders/cart',
            [
                'cart_ids' => to_array($cart_ids),
                'address' => $address['id']
            ],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]);
        $result = $this->getResponseData();

        $this->dump();
        $this->assertResponseOk();

        $temp_order_id = $result['data']['temp_order_id'];

        $this->json('post', 'mall/orders', ['temp_order_id' => $temp_order_id], ['Authorization' => 'Bearer ' . $this->getToken($user_id)]);

        $order = $this->getResponseData();

        $this->assertResponseStatus(201);

        return $order['data'];
    }

    public function it_can_add_a_sku_to_cart()
    {
        $user_id = 1;
        $this->json('POST', 'mall/cart', ['product_sku_id' => 2, 'quantity' => 100], ['Authorization' => 'Bearer ' . $this->getToken($user_id)]);
        $result = $this->getResponseData();
        $this->assertResponseStatus(201);
        return $result['data']['id'];
    }

    /** @test */
    public function it_can_get_pingxx_paid_charge()
    {
        $user_id = 1;
        $order = $this->ite_can_create_a_cart_order();
        $channel = 'wx_pub_qr';

        $this->json('post', 'mall/orders/' . $order['order_no']. '/checkout', ['channel' => $channel], ['Authorization' => 'Bearer ' . $this->getToken($user_id)]);

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

        $this->json('post', 'mall/orders/' . $order['order_no']. '/checkout', ['channel' => $channel], ['Authorization' => 'Bearer ' . $this->getToken($user_id)]);

        $this->dumpResponse();

    }

}
