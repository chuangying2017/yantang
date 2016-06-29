<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PreorderApiTest extends TestCase {

    use DatabaseTransactions;


    /** @test */
    public function it_can_get_preorders_list()
    {
        $this->json('get', 'subscribe/preorders', [], $this->getAuthHeader());

        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_check_station_when_create_a_preorder()
    {
        $out_side = [23.159711, 113.333818];

        $data = [
            'name' => 'asda',
            'phone' => 1,
            'address' => 'asdad',
            'longitude' => $out_side[0],
            'latitude' => $out_side[1],
            'district' => 1
        ];

        $this->json('post', 'subscribe/preorders',
            $data,
            $this->getAuthHeader()
        );

        $this->assertResponseStatus(404);

    }

    /** @test */
    public function it_can_create_a_preorder()
    {
        $user_id = 1;
        $inside = [23.157195, 113.330319];
        $data = [
            'name' => 'asda',
            'phone' => 1,
            'address' => 'asdad',
            'longitude' => $inside[0],
            'latitude' => $inside[1],
            'district' => 1
        ];
        $this->json('post', 'subscribe/preorders',
            $data,
            $this->getAuthHeader()
        );

        $this->assertResponseStatus(201);
    }

    protected function getSkusData()
    {
        return [
            [
                'weekday' => 0,
                'day_time' => 0,
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
                'day_time' => 1,
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
                'day_time' => 0,
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
