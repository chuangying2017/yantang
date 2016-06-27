<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PreorderApiTest extends TestCase {

    /** @test */
    public function it_can_create_a_preorder()
    {

        $data = [
            [
                'weekday' => 0,
                'day_time' => 0,
                'product_skus' => [
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
                'product_skus' => [
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
