<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StoreApiTest extends TestCase {


    /** @test */
    public function it_can_get_a_store_info()
    {
        $user_id = 1;

        $this->json('GET', 'store/info',
            [],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        if ($this->getStatus() == 403) {
            $this->it_can_bind_user_to_store();
            $this->json('GET', 'store/info',
                [],
                ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
            );
        }

        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_see_order_ticket_info()
    {
        $user_id = 1;

        $ticket_no = '10716062364115856865812';
        $this->json('GET', 'store/tickets/' . $ticket_no,
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
        $this->json('PUT', 'store/tickets/' . $ticket_no,
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
        $this->json('get', '/store/exchange',
            [],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->dumpResponse();
        $this->assertResponseOk();
    }


    /** @test */
    public function it_can_bind_user_to_store()
    {
        $user_id = 1;
        $token = $this->getToken($user_id);
        $store = \App\Models\Store::create();


        $url = 'store/' . $store['id'] . '/bind';
        $response = $this->json('get', $url,
            ['bind_token' => generate_bind_token($store['id'])],
            ['Authorization' => 'Bearer ' . $token]
        );

        $this->dumpResponse();

        $this->assertResponseOk();

        $this->json('POST', $url,
            ['bind_token' => generate_bind_token($store['id'])],
            ['Authorization' => 'Bearer ' . $token]
        );
        $this->assertResponseStatus(201);

        $this->json('GET', 'store/info', [], ['Authorization' => 'Bearer ' . $token]);

        $result = $this->getResponseData();

        $this->assertEquals($store['id'], $result['data']['id']);
    }
}
