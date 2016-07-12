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
        $out_side = [23.159711, 113.333818];

        $data = [
            'name' => 'asda',
            'phone' => 1,
            'detail' => 'asdad',
            'longitude' => $out_side[0],
            'latitude' => $out_side[1],
            'district_id' => 1
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
        $inside = [23.157195, 113.330319];
        $data = [
            'name' => 'asda',
            'phone' => 1,
            'detail' => 'asdad',
            'longitude' => $inside[0],
            'latitude' => $inside[1],
            'district_id' => 1
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

}
