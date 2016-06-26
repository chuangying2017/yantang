<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminUserApiTest extends TestCase {

    /** @test */
    public function it_can_get_all_users()
    {
        $user_id = 1;

        $this->json('get', 'admin/access/users',
            ['status' => 1],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->dump();
    }

    /** @test */
    public function it_can_create_a_user()
    {
        $data = [
            'username' => '门店管理专员 Will',
            'phone' => '13277548382',
            'password' => '1234',
            'password_confirmation' => '1234',
            'assignees_roles' => [3]
        ];

        $user_id = 1;

        $this->json('post', 'admin/access/users',
            $data,
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->dumpResponse();
        $this->assertResponseStatus(201);
    }

    /** @test */
    public function it_can_get_all_roles()
    {
        $user_id = 1;

        $this->json('get', 'admin/access/roles',
            [],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->dumpResponse();
        $this->assertResponseStatus(200);
    }
}
