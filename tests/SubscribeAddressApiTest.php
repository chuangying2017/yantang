<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SubscribeAddressApiTest extends TestCase {

    use DatabaseTransactions;


    /** @test */
    public function it_can_get_subscribe_address_list()
    {
        $this->it_can_create_a_subscribe_address();
        $this->json('get', 'subscribe/address', [], $this->getAuthHeader());

        $this->seeJsonStructure(['data' => [['longitude', 'station_id']]]);
        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_check_station_when_create_a_subscribe_address()
    {
        $this->it_can_create_another_station();

        $out_side = [23.091847,113.306186];

        $data = [
            'name' => 'asda',
            'phone' => 1,
            'detail' => 'asdad',
            'street' => 'asdad',
            'longitude' => $out_side[0],
            'latitude' => $out_side[1],
            'district_id' => 440105
        ];
        $this->json('post', 'subscribe/address',
            $data,
            $this->getAuthHeader()
        );
        $this->assertResponseStatus(404);
    }

    /** @test */
    public function it_can_create_a_subscribe_address()
    {
        $this->it_can_create_another_station();


        $inside = [23.099075,113.291048];
//        $out_side = [23.151668243459564, 113.32917949894649];

        $data = [
            'name' => 'asda',
            'phone' => 1,
            'street' => '街道',
            'detail' => 'adasd',
            'longitude' => $inside[0],
            'latitude' => $inside[1],
            'district_id' => 440105
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
    public function it_can_get_district_lists()
    {
        $this->json('get', 'subscribe/districts', [], $this->getAuthHeader());

        $this->echoJson();
        $this->assertResponseOk();
        $this->seeJsonStructure(['data' => [['id', 'name', 'station_count']]]);
    }


    /** @test */
    public function it_can_create_a_station()
    {
        $this->json('post', 'admin/stations',
            [
                'name' => '林和服务部',
                'address' => '林和服务部地址',
                'director' => '林和服务部负责人',
                'district_id' => 440103,
                'geo' => [
                    [
                        23.126951,
                        113.3334
                    ],
                    [
                        23.127684,
                        113.321343
                    ],
                    [
                        23.129756,
                        113.31539
                    ],
                    [
                        23.134055,
                        113.315457
                    ],
                    [
                        23.153972,
                        113.313943
                    ],
                    [
                        23.153323,
                        113.318013
                    ],
                    [
                        23.15456,
                        113.322184
                    ],
                    [
                        23.148574,
                        113.333818
                    ],
                    [
                        23.144603,
                        113.332379
                    ],
                    [
                        23.133171,
                        113.334276
                    ]
                ],
                'cover_image' => '11111',
                'phone' => mt_rand(10000000000, 19999999999),
                'longitude' => 23.140497,
                'latitude' => 113.3214161,
            ],
            $this->getAuthHeader()
        );

        $this->assertResponseStatus(201);

        $this->seeInDatabase('stations', ['district_id' => 440103]);

        return $this->getResponseData('data');
    }

    /** @test */
    public function it_can_create_another_station()
    {
        $this->json('post', 'admin/stations',
            [
                'name' => '客村服务部',
                'address' => '客村服务部地址',
                'director' => '客村服务部负责人',
                'district_id' => 440105,
                'geo' => [
                    [23.107875, 113.286599], [23.105901, 113.316039], [23.095796, 113.315180], [23.094848, 113.304537], [23.091049, 113.297671], [23.093673, 113.284236],
                ],
                'cover_image' => '11111',
                'phone' => mt_rand(10000000000, 19999999999),
                'longitude' => 23.095645,
                'latitude' => 113.313213,
            ],
            $this->getAuthHeader()
        );

        $this->assertResponseStatus(201);

        $this->seeInDatabase('stations', ['district_id' => 440105]);

        return $this->getResponseData('data');
    }

}
