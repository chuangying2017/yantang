<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BindStoreApiTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_bind_user_to_store()
    {
        $token = $this->getToken();
        $user = JWTAuth::toUser($token);
        $store = \App\Models\Store::create();


        $this->get('store/' . $store['id'] . '/bind?token=' . $token);
        $url = 'store/' . $store['id'] . '/bind';
        $response = $this->json('get', $url,
            ['bind_token' => generate_bind_token($store['id'])],
            ['Authorization' => 'Bearer ' . $token]
        );
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
