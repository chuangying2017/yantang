<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CartOrderApiTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function ite_can_checkout_the_cart_items()
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

        $this->dumpResponse();


        $this->assertResponseOk();

        $temp_order_id = $result['data']['temp_order_id'];

        $this->json('post', 'mall/orders', ['temp_order_id' => $temp_order_id], ['Authorization' => 'Bearer ' . $this->getToken($user_id)]);

        $this->dumpResponse();

        $this->assertResponseStatus(201);
    }

    public function it_can_add_a_sku_to_cart()
    {
        $user_id = 1;
        $this->json('POST', 'mall/cart', ['product_sku_id' => 2, 'quantity' => 100], ['Authorization' => 'Bearer ' . $this->getToken($user_id)]);
        $result = $this->getResponseData();
        $this->assertResponseStatus(201);
        return $result['data']['id'];
    }


}
