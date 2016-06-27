<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AddressApiTest extends TestCase {

    /** @test */
    public function it_can_create_a_address()
    {
        $user_id = 1;
        $this->json('post', 'users/address',
            ['name' => 'asda',
                'phone' => 'asdadad',
                'province' => 'adad',
                'city' => 'adasda',
                'district' => 'adasda',
                'detail' => 'adasda',
                'zip' => 'adasda',
                'display_name' => 'adasda'
            ],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->seeInDatabase('addresses', ['user_id' => $user_id, 'name' => 'asda']);
    }
}
