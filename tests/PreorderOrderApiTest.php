<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PreorderOrderApiTest extends TestCase {

    /** @test */
    public function it_can_create_a_preorder_order()
    {
        $data = [
            'skus' => [
                ['product_sku_id' => 2, 'quantity' => 60, 'per_day' => 2],
                ['product_sku_id' => 3, 'quantity' => 90, 'per_day' => 3],
            ],
            'address_id' => 2,
            'station_id' => 1,
            'weekday_type' => 'all',
            'start_time' => '2016-07-10'
        ];

        $this->json('post', 'subscribe/orders', $data, $this->getAuthHeader());

        $this->assertResponseStatus(201);
    }
}
