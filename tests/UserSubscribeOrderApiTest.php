<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserSubscribeOrderApiTest extends TestCase {

    /** @test */
    public function it_can_create_a_preorder()
    {
        $user_id = 1;
        $token = $this->getToken($user_id);


        $preorder_data = [
            'user_id' => $user_id,
            'name' => 'name',
            'phone' => '13222222222',
            'district_id' => 1,
            'address' => 'shenzhen nanshan',
            'longitude' => 1,
            'latitude' => 2
        ];

        $response = $this->json('post', 'subscribe/preorders',
            $preorder_data,
            ['Authorization' => 'Bearer ' . $token]
        );


    }
}
