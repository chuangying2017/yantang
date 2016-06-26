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

        $this->json('GET', 'station/info',
            [],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        if ($this->response->getStatusCode() == 403) {
            $this->it_can_bind_user_to_station();
            $this->json('GET', 'station/info',
                [],
                ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
            );
        }
        $this->dumpResponse();

        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_see_order_ticket_info()
    {
        $user_id = 1;

        $ticket_no = '10716062364115856865812';
        $this->json('GET', 'station/tickets/' . $ticket_no,
            [],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->dumpResponse();
        $this->assertResponseOk();

    }

    /** @test */
    public function it_can_exchange_a_ticket()
    {
        $user_id = 1;

        $ticket_no = '10716062364115856865812';
        $this->json('PUT', 'station/tickets/' . $ticket_no,
            [],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->dumpResponse();
        $this->assertResponseOk();

    }

    /** @test */
    public function it_can_get_exchange_lists()
    {
        $user_id = 1;

//        $ticket_no = '10716062364115856865812';
        $this->json('get', '/station/exchange',
            [],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->dumpResponse();
        $this->assertResponseOk();
    }


    /** @test */
    public function it_can_bind_user_to_station()
    {
        $user_id = 1;
        $token = $this->getToken($user_id);
        $station = \App\Models\Store::create();


        $url = 'station/' . $station['id'] . '/bind';
        $response = $this->json('get', $url,
            ['bind_token' => generate_bind_token($station['id'])],
            ['Authorization' => 'Bearer ' . $token]
        );

        $this->dumpResponse();

        $this->assertResponseOk();

        $this->json('POST', $url,
            ['bind_token' => generate_bind_token($station['id'])],
            ['Authorization' => 'Bearer ' . $token]
        );
        $this->assertResponseStatus(201);

        $this->json('GET', 'station/info', [], ['Authorization' => 'Bearer ' . $token]);

        $result = $this->getResponseData();

        $this->assertEquals($station['id'], $result['data']['id']);
    }
}
