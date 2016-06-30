<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminUserApiTest extends TestCase {

    use DatabaseTransactions;

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

        $this->assertResponseStatus(201);

        return $this->getResponseData('data');
    }

    /** @test */
    public function it_can_update_a_user()
    {
        $user = $this->it_can_create_a_user();

        $data = [
            'username' => '门店管理专员 Will',
            'phone' => '13277548383',
            'password' => '1234',
            'password_confirmation' => '1234',
            'assignees_roles' => [3]
        ];

        $this->json('put', 'admin/access/users/' . $user['id'],
            $data,
            ['Authorization' => 'Bearer ' . $this->getToken()]
        );

        $this->assertResponseStatus(200);

        $this->seeInDatabase('users', ['phone' => 13277548383, 'id' => $user['id']]);

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

    /** @test */
    public function it_can_create_a_role()
    {
        $this->json('post', 'admin/access/roles',
            [
                'name' => 'test1',
                'associated-permissions' => 'none',
                'permissions' => ''
            ],
            $this->getAuthHeader()
        );

        $this->assertResponseStatus(201);
    }
}
