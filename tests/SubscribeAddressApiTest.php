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

        $out_side = [23.091847, 113.306186];

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
        $station = $this->it_can_create_a_station();


        $inside = [23.187157, 113.445869];
        $out_side = [23.162627, 113.205182];

        $data = [
            'name' => 'asda',
            'phone' => 1,
            'street' => '街道',
            'detail' => 'adasd',
            'longitude' => $inside[0],
            'latitude' => $inside[1],
            'district_id' => 440103
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
                'geo' => [[23.178506, 113.453916], [23.17823, 113.450397], [23.177342, 113.444754], [23.177441, 113.438703], [23.177954, 113.436214], [23.178644, 113.434604], [23.17892, 113.433703], [23.179709, 113.429798], [23.184227, 113.427845], [23.185371, 113.426858], [23.187757, 113.425807], [23.188842, 113.425571], [23.190321, 113.425957], [23.191958, 113.425914], [23.19399, 113.426129], [23.196574, 113.425828], [23.198388, 113.425828], [23.199572, 113.425656], [23.199138, 113.426279], [23.197284, 113.428618], [23.195903, 113.430377], [23.195114, 113.433038], [23.195016, 113.434154], [23.19324, 113.437737], [23.19259, 113.439497], [23.191919, 113.442393], [23.191682, 113.44441], [23.191544, 113.446535], [23.191189, 113.448316], [23.191091, 113.450333], [23.191051, 113.451728], [23.190026, 113.453959], [23.189256, 113.455375], [23.188171, 113.456126], [23.187718, 113.456427], [23.183043, 113.459667], [23.180005, 113.459495]],
                'cover_image' => '11111',
                'phone' => mt_rand(10000000000, 19999999999),
                'longitude' => 23.190769,
                'latitude' => 113.421578,
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
