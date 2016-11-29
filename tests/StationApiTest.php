<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StationApiTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_get_a_station_info()
    {
        $user_id = 1;

        $this->json('GET', 'stations/info',
            [],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        if ($this->response->getStatusCode() == 403) {
            $this->it_can_bind_user_to_station();
            $this->json('GET', 'stations/info',
                [],
                ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
            );
        }
        $this->echoJson();

        $this->assertResponseOk();
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

        $this->echoJson();

        $this->assertResponseOk();


        $this->json('POST', $url,
            ['bind_token' => generate_bind_token($station['id'])],
            ['Authorization' => 'Bearer ' . $token]
        );

//        $this->dump();

        $this->assertResponseStatus(201);

        $this->json('GET', 'stations/info', [], ['Authorization' => 'Bearer ' . $token]);

        $result = $this->getResponseData();

        $this->assertEquals($station['id'], $result['data']['id']);
    }


}
