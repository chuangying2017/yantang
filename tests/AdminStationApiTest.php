<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminStationApiTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_get_all_stations()
    {
        $user_id = 1;
        $this->json('get', 'admin/stations',
            [],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]);
        $this->assertResponseOk();
        $this->assertResponseStatus(200);
    }

    /** @test */
    public function it_can_create_a_station()
    {
        $user_id = 1;
        $data = [
             'name' => '萨沙',
            'desc' => '妙手',
            'director' => 'dircor',
            'address' => 'dircor',
            'cover_image' => 'dircor',
            'longitude' => 'dircor',
            'latitude' => 'dircor',
        ];
        $this->json('post', 'admin/stations',
            $data,
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]);

        $result = $this->getResponseData('data');

        $this->assertResponseStatus(201);
        $this->seeInDatabase('station', ['name' => '萨沙']);
    }



}
