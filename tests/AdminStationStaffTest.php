<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminStationStaffTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_create_a_staff()
    {

        $station = $this->it_can_bind_user_to_station();

        $user_id = 1;
        $this->json('post', 'stations/staffs',
            [
                'name' => '服务部' . mt_rand(),
                'phone' => '服务部描述' . mt_rand()
            ],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->assertResponseStatus(201);

        return $this->getResponseData('data');
    }

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

        $this->assertResponseStatus(201);

        return $this->getResponseData('data');
    }

    /** @test */
    public function it_can_bind_user_to_station()
    {
        $user_id = 1;
        $token = $this->getToken($user_id);
        $station = \App\Models\Subscribe\Station::create();


        $url = 'stations/' . $station['id'] . '/bind';
        $response = $this->json('get', $url,
            ['bind_token' => generate_bind_token($station['id'])],
            ['Authorization' => 'Bearer ' . $token]
        );

        $this->assertResponseOk();

        $this->json('POST', $url,
            ['bind_token' => generate_bind_token($station['id'])],
            ['Authorization' => 'Bearer ' . $token]
        );


        $this->assertResponseStatus(201);


        $this->json('GET', 'stations/info', [], ['Authorization' => 'Bearer ' . $token]);

        $result = $this->getResponseData();

        $this->assertEquals($station['id'], $result['data']['id']);

        return $station;
    }


    /** @test */
    public function it_can_bind_user_to_staff()
    {
        $user_id = 1;
        $token = $this->getToken($user_id);

        $staff = $this->it_can_create_a_staff();

        $url = 'stations/staffs/' . $staff['id'] . '/bind';
        $response = $this->json('get', $url,
            ['bind_token' => generate_bind_token($staff['id'])],
            ['Authorization' => 'Bearer ' . $token]
        );


        $this->assertResponseOk();

        $this->json('POST', $url,
            ['bind_token' => generate_bind_token($staff['id'])],
            ['Authorization' => 'Bearer ' . $token]
        );


        $this->assertResponseStatus(201);

        $this->json('GET', 'stations/staffs/info', [], ['Authorization' => 'Bearer ' . $token]);

        $result = $this->getResponseData();

        $this->assertEquals($staff['id'], $result['data']['id']);

        return $staff;
    }
}
