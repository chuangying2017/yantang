<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CategoryApiTest extends TestCase {

    /** @test */
    public function it_can_create_a_category()
    {
        $data = [
            'name' => '鲜奶',
            'pid' => null,
            'cover_image' => 'asdaa',
            'desc' => '鲜奶'
        ];

//        $this->json('post', '')
    }
}
