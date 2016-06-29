<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CategoryApiTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_create_a_category()
    {
        $user_id = 1;

        $data = [
            'name' => '鲜奶',
            'pid' => 1,
            'cover_image' => 'asdaa',
            'desc' => '鲜奶',
            'priority' => 23,
        ];

        $this->json('post', 'admin/products/cats',
            $data,
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]);


        $this->dump();
        $this->assertResponseStatus(200);
    }


}
