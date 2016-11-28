<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminStationApiTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_get_districts()
    {
        $this->json('post', 'admin/districts',
            ['name' => '天河'],
            $this->getAuthHeader()
        );

        $this->assertResponseStatus(201);

        $this->json('get', 'admin/districts',
            [],
            $this->getAuthHeader()
        );

        $this->assertResponseStatus(200);
    }

    /** @test */
    public function it_can_create_a_station()
    {
        $this->json('post', 'admin/stations',
            [
                'name' => '服务部' . mt_rand(),
                'address' => '服务部地址' . mt_rand(),
                'director' => '服务部负责人' . mt_rand(),
                'district_id' => 1,
                'geo' => [1231],
                'cover_image' => '11111',
                'phone' => mt_rand(10000000000, 19999999999),
                'longitude' => mt_rand(110000, 999999) / 10000,
                'latitude' => mt_rand(110000, 999999) / 10000,
            ],
            $this->getAuthHeader()
        );

        $this->assertResponseStatus(201);

        return $this->getResponseData('data');
    }

    /** @test */
    public function it_can_update_a_station()
    {
        $station = $this->it_can_create_a_station();
        $this->json('put', 'admin/stations/' . $station['id'],
            [
                'name' => '服务部' . mt_rand(),
                'address' => '服务部地址' . mt_rand(),
                'director' => '服务部负责人' . mt_rand(),
                'district_id' => 1,
                'geo' => [1231],
                'cover_image' => '11111',
                'phone' => mt_rand(10000000000, 19999999999),
                'longitude' => mt_rand(110000, 999999) / 10000,
                'latitude' => mt_rand(110000, 999999) / 10000,
            ],
            $this->getAuthHeader()
        );

        $this->assertResponseStatus(200);

        return $this->getResponseData('data');
    }

    /** @test */
    public function it_can_get_station_list()
    {
        $this->it_can_create_a_station();
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
    public function it_can_unbind_user_of_station()
    {
        $user_id = 1;
        $station_id = 1;
        $this->seeInDatabase('station_user', ['user_id' => $user_id, 'station_id' => $station_id]);
        $this->seeInDatabase('assigned_roles', ['user_id' => $user_id, 'role_id' => \App\Repositories\Backend\AccessProtocol::ID_ROLE_OF_STATION]);

        $this->json('put', 'admin/stations/' . $station_id . '/unbind', [
            'user' => 'all'
        ], $this->getAuthHeader());

        $this->assertResponseStatus(204);

        $this->notSeeInDatabase('station_user', ['user_id' => $user_id, 'station_id' => $station_id]);
        $this->notSeeInDatabase('assigned_roles', ['user_id' => $user_id, 'role_id' => \App\Repositories\Backend\AccessProtocol::ID_ROLE_OF_STATION]);
    }

    /** @test */
    public function it_can_get_a_station_detail()
    {
        $station_id = 1;
        $this->json('get', 'admin/stations/' . $station_id, [], $this->getAuthHeader());

        $this->dump();
    }

    /** @test */
    public function it_can_set_station_kpi()
    {
        $station_id = 4;
        $this->json('put', 'admin/stations/' . $station_id . '/kpi', [
            'user_count_kpi' => 30
        ], $this->getAuthHeader());

        $this->dump();
    }
}
