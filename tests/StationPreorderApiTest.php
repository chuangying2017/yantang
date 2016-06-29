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

        $this->assertResponseOk();
        $this->seeInDatabase('preorders', ['id' => $order_id, 'status' => \App\Services\Preorder\PreorderProtocol::ORDER_STATUS_OF_SHIPPING]);
        $this->seeInDatabase('preorder_assign', ['preorder_id' => $order_id, 'status' => \App\Services\Preorder\PreorderProtocol::ASSIGN_STATUS_OF_CONFIRM]);
        $this->seeInDatabase('preorder_skus', ['preorder_id' => $order_id, 'weekday' => 5, 'daytime' => 0, 'product_sku_id' => 2, 'quantity' => 1]);
        $this->seeInDatabase('preorder_skus', ['preorder_id' => $order_id, 'weekday' => 5, 'daytime' => 0, 'product_sku_id' => 3, 'quantity' => 2]);
        $this->seeInDatabase('preorder_skus', ['preorder_id' => $order_id, 'weekday' => 0, 'daytime' => 0, 'product_sku_id' => 2, 'quantity' => 1]);
        $this->seeInDatabase('preorder_skus', ['preorder_id' => $order_id, 'weekday' => 0, 'daytime' => 0, 'product_sku_id' => 3, 'quantity' => 2]);
        $this->seeInDatabase('preorder_skus', ['preorder_id' => $order_id, 'weekday' => 0, 'daytime' => 1, 'product_sku_id' => 2, 'quantity' => 1]);
        $this->seeInDatabase('preorder_skus', ['preorder_id' => $order_id, 'weekday' => 0, 'daytime' => 1, 'product_sku_id' => 3, 'quantity' => 2]);

        return $order_id;
    }


    /** @test */
    public function it_can_update_a_confirmed_preorder()
    {
        $order_id = 2;

        $start_time = '2016-07-02';
        $data = [
            'start_time' => $start_time,
            'end_time' => '2016-09-01',
            'product_skus' => $this->getUpdateSkusData()
        ];

        $this->json('put', 'stations/preorders/' . $order_id,
            $data,
            $this->getAuthHeader()
        );

        $new_order = $this->getResponseData('data');

        $this->assertResponseOk();

        $this->seeInDatabase('preorders', ['id' => $order_id, 'status' => \App\Services\Preorder\PreorderProtocol::ORDER_STATUS_OF_SHIPPING, 'end_time' => '2016-07-01']);
        $this->seeInDatabase('preorder_assign', ['preorder_id' => $order_id, 'status' => \App\Services\Preorder\PreorderProtocol::ASSIGN_STATUS_OF_CONFIRM]);
        $this->seeInDatabase('preorder_skus', ['preorder_id' => $order_id, 'weekday' => 5, 'daytime' => 0, 'product_sku_id' => 2, 'quantity' => 1]);
        $this->seeInDatabase('preorder_skus', ['preorder_id' => $order_id, 'weekday' => 5, 'daytime' => 0, 'product_sku_id' => 3, 'quantity' => 2]);
        $this->seeInDatabase('preorder_skus', ['preorder_id' => $order_id, 'weekday' => 0, 'daytime' => 0, 'product_sku_id' => 2, 'quantity' => 1]);
        $this->seeInDatabase('preorder_skus', ['preorder_id' => $order_id, 'weekday' => 0, 'daytime' => 0, 'product_sku_id' => 3, 'quantity' => 2]);
        $this->seeInDatabase('preorder_skus', ['preorder_id' => $order_id, 'weekday' => 0, 'daytime' => 1, 'product_sku_id' => 2, 'quantity' => 1]);
        $this->seeInDatabase('preorder_skus', ['preorder_id' => $order_id, 'weekday' => 0, 'daytime' => 1, 'product_sku_id' => 3, 'quantity' => 2]);


        $this->seeInDatabase('preorders', ['id' => $new_order['id'], 'status' => \App\Services\Preorder\PreorderProtocol::ORDER_STATUS_OF_SHIPPING, 'start_time' => $start_time]);
        $this->seeInDatabase('preorder_assign', ['preorder_id' => $new_order['id'], 'status' => \App\Services\Preorder\PreorderProtocol::ASSIGN_STATUS_OF_CONFIRM]);
        $this->seeInDatabase('preorder_skus', ['preorder_id' => $new_order['id'], 'weekday' => 5, 'daytime' => 0, 'product_sku_id' => 2, 'quantity' => 1]);
        $this->seeInDatabase('preorder_skus', ['preorder_id' => $new_order['id'], 'weekday' => 5, 'daytime' => 0, 'product_sku_id' => 3, 'quantity' => 2]);
        $this->seeInDatabase('preorder_skus', ['preorder_id' => $new_order['id'], 'weekday' => 1, 'daytime' => 0, 'product_sku_id' => 2, 'quantity' => 3]);
        $this->seeInDatabase('preorder_skus', ['preorder_id' => $new_order['id'], 'weekday' => 1, 'daytime' => 0, 'product_sku_id' => 3, 'quantity' => 2]);
        $this->seeInDatabase('preorder_skus', ['preorder_id' => $new_order['id'], 'weekday' => 0, 'daytime' => 1, 'product_sku_id' => 2, 'quantity' => 1]);
        $this->seeInDatabase('preorder_skus', ['preorder_id' => $new_order['id'], 'weekday' => 0, 'daytime' => 1, 'product_sku_id' => 3, 'quantity' => 2]);

        return $order_id;
    }

    /** @test */
    public function it_can_get_a_preorder()
    {
        $order_id = $this->it_can_confirm_and_update_a_preorder();
        $this->json('get', 'stations/preorders/' . $order_id, [], $this->getAuthHeader());

        $this->assertResponseOk();
        $this->seeJsonStructure(['data' => ['skus']]);

    }

    /** @test */
    public function it_can_pause_a_preorder()
    {
        $order_id = 2;

        $data = [
            'pause_time' => '2016-07-02',
            'restart_time' => '2016-07-04'
        ];

        $this->json('put', 'stations/preorders/' . $order_id . '/pause',
            $data,
            $this->getAuthHeader()
        );

        $this->assertResponseOk();


        $this->seeInDatabase('preorders', ['id' => $order_id, 'end_time' => '2016-07-01']);

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

    protected function getUpdateSkusData()
    {
        return [
            [
                'weekday' => 1,
                'daytime' => 0,
                'skus' => [
                    [
                        'product_sku_id' => 2,
                        'quantity' => 3
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

