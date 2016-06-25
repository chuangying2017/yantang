<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminStationApiTest extends TestCase
{
//    use DatabaseTransactions;

    /** @test */
    public function it_can_create_a_station()
    {
        $user_id = 1;

        $this->json('post', 'admin/stations',
            [
                'name' => '服务部' . mt_rand(),
                'desc' => '服务部描述' . mt_rand(),
                'address' => '服务部地址' . mt_rand(),
                'director' => '服务部负责人' . mt_rand(),
                'district_id' => mt_rand(1, 11),
                'cover_image' => '11111',
                'phone' => mt_rand(10000000000, 19999999999),
                'longitude' => mt_rand(110000, 999999) / 10000,
                'latitude' => mt_rand(110000, 999999) / 10000,
            ],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->dumpResponse();

        $this->assertResponseStatus(201);
    }

    /** @test */
    public function it_can_get_station_list()
    {
        $user_id = 1;
        $district_id = 1;
        $per_page = 10;
        $keyword = '';

        $this->json('get', 'admin/stations',
            [
                'district_id' => $district_id,
                'keyword' => $keyword,
                'paginate' => $per_page,
            ],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->dumpResponse();

    }

    /** @test */
    public function it_can_bind_station()
    {
        $user_id = 1;
        $this->json('get', 'stations/bind_station',
            [
                'station_id' => '2',
            ],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->dumpResponse();

        $this->assertResponseStatus(201);
    }
}
