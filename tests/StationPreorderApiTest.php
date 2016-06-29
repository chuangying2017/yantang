<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StationPreorderApiTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function ite_can_get_all_not_confirm_orders_of_station()
    {
        $this->json('get', 'stations/preorders',
            [],
            $this->getAuthHeader()
        );

        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_reject_a_order()
    {
        $order_id = 1;

        $this->json('put', 'stations/preorders/' . $order_id . '/reject',
            ['memo' => 'baba'],
            $this->getAuthHeader()
        );

        $this->assertResponseOk();
        $this->seeInDatabase('preorder_assign', ['preorder_id' => $order_id, 'status' => \App\Services\Preorder\PreorderProtocol::ASSIGN_STATUS_OF_REJECT]);
    }

    /** @test */
    public function it_can_confirm_and_update_a_preorder()
    {
        $order_id = 2;

        $data = [
            'start_time' => '2016-07-01',
            'end_time' => '2016-08-01',
            'product_skus' => $this->getSkusData()
        ];

        $this->json('put', 'stations/preorders/' . $order_id,
            $data,
            $this->getAuthHeader()
        );

//        $this->dump();

        $this->assertResponseOk();
        $this->seeInDatabase('preorders', ['id' => $order_id, 'status' => \App\Services\Preorder\PreorderProtocol::ORDER_STATUS_OF_SHIPPING]);
        $this->seeInDatabase('preorder_assign', ['preorder_id' => $order_id, 'status' => \App\Services\Preorder\PreorderProtocol::ASSIGN_STATUS_OF_CONFIRM]);
        $this->seeInDatabase('preorder_skus', ['preorder_id' => $order_id, 'weekday' => 5, 'daytime' => 0, 'product_sku_id' => 2, 'quantity' => 1]);
        $this->seeInDatabase('preorder_skus', ['preorder_id' => $order_id, 'weekday' => 5, 'daytime' => 0, 'product_sku_id' => 3, 'quantity' => 2]);
        $this->seeInDatabase('preorder_skus', ['preorder_id' => $order_id, 'weekday' => 0, 'daytime' => 0, 'product_sku_id' => 2, 'quantity' => 1]);
        $this->seeInDatabase('preorder_skus', ['preorder_id' => $order_id, 'weekday' => 0, 'daytime' => 0, 'product_sku_id' => 3, 'quantity' => 2]);
        $this->seeInDatabase('preorder_skus', ['preorder_id' => $order_id, 'weekday' => 0, 'daytime' => 1, 'product_sku_id' => 2, 'quantity' => 1]);
        $this->seeInDatabase('preorder_skus', ['preorder_id' => $order_id, 'weekday' => 0, 'daytime' => 1, 'product_sku_id' => 3, 'quantity' => 2]);

    }

    /** @test */
    public function it_can_bind_user_to_station()
    {
        $user_id = 1;
        $station_id = 1;
        $token = $this->getToken($user_id);

        $url = 'stations/' . $station_id . '/bind';
        $response = $this->json('get', $url,
            ['bind_token' => generate_bind_token($station_id)],
            ['Authorization' => 'Bearer ' . $token]
        );

        $this->assertResponseOk();


        $this->json('POST', $url,
            ['bind_token' => generate_bind_token($station_id)],
            ['Authorization' => 'Bearer ' . $token]
        );

        $this->assertResponseStatus(201);

        $this->json('GET', 'stations/info', [], ['Authorization' => 'Bearer ' . $token]);

        $result = $this->getResponseData();

        $this->assertEquals($station_id, $result['data']['id']);
    }



    protected function getSkusData()
    {
        return [
            [
                'weekday' => 0,
                'daytime' => 0,
                'skus' => [
                    [
                        'product_sku_id' => 2,
                        'quantity' => 1
                    ],
                    [
                        'product_sku_id' => 3,
                        'quantity' => 2
                    ]
                ]
            ],
            [
                'weekday' => 0,
                'daytime' => 1,
                'skus' => [
                    [
                        'product_sku_id' => 2,
                        'quantity' => 1
                    ],
                    [
                        'product_sku_id' => 3,
                        'quantity' => 2
                    ]
                ]
            ],
            [
                'weekday' => 5,
                'daytime' => 0,
                'skus' => [
                    [
                        'product_sku_id' => 2,
                        'quantity' => 1
                    ],
                    [
                        'product_sku_id' => 3,
                        'quantity' => 2
                    ]
                ]
            ],
        ];
    }
}

