<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CartOrderApiTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function ite_can_checkout_the_cart_items()
    {
        $cart_ids = [12];
        $user_id = 68;
        $address = \App\Models\Client\Address::create(['user_id' => $user_id]);

        $this->json('post', 'mall/orders/cart',
            [
                'cart_ids' => $cart_ids,
                'address_id' => $address['id']
            ],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]);
        $result = $this->getResponseData();

        $this->assertResponseOk();

        $temp_order_id = $result['data']['temp_order_id'];

        $this->json('post', 'mall/orders', ['temp_order_id' => $temp_order_id], ['Authorization' => 'Bearer ' . $this->getToken($user_id)]);

        $this->dumpResponse();

        $this->assertResponseStatus(201);
    }
}
