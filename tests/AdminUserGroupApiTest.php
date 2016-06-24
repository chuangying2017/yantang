<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminUserGroupApiTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_create_a_user_group()
    {
        $user_id = 1;

        $this->json('post', 'admin/clients/groups',
            ['name' => '高级'],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->assertResponseStatus(201);
        $this->seeJsonStructure(['data' => ['name']]);

        return $this->getResponseData('data');
    }

    /** @test */
    public function it_can_get_user_group_lists()
    {

        $this->it_can_create_a_user_group();
        $user_id = 1;

        $this->json('get', 'admin/clients/groups',
            [],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->assertResponseStatus(200);
    }

    /** @test */
    public function it_can_prevent_multi_name()
    {
        $user_id = 1;

        $this->it_can_create_a_user_group();

        $this->json('post', 'admin/clients/groups',
            ['name' => '高级'],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->assertResponseStatus(422);
    }

    /** @test */
    public function it_can_update_a_group()
    {
        $group = $this->it_can_create_a_user_group();

        $user_id = 1;

        $this->json('put', 'admin/clients/groups/' . $group['id'],
            ['name' => '高级会员'],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->assertResponseStatus(200);

        $result = $this->getResponseData('data');

        $this->assertEquals('高级会员', $result['name']);
    }

    /** @test */
    public function it_can_get_group_users()
    {
        $user_id = 1;

        $group = $this->it_can_add_users_to_group();

        $this->json('get', 'admin/clients/groups/' . $group['id'] . '/users',
            [],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->assertResponseStatus(200);
    }

    /** @test */
    public function it_can_add_users_to_group()
    {
        $user_id = 1;

        $group = $this->it_can_create_a_user_group();

        $this->json('post', 'admin/clients/groups/' . $group['id'] . '/users',
            ['users' => [1,3]],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->dumpResponse();

        $this->json('post', 'admin/clients/groups/' . $group['id'] . '/users',
            ['users' => [1, 2, 3]],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->assertResponseStatus(201);

        return $group;
    }
}
