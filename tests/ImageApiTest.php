<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ImageApiTest extends TestCase {

    /** @test */
    public function it_can_get_a_upload_token()
    {
        $user_id = 1;
        $this->json('get', 'images/token',
            [],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->dumpResponse();
    }


    /** @test */
    public function it_can_get_images_lists()
    {
        $user_id = 1;
        $this->json('get', 'admin/images',
            [],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->dumpResponse();
    }
}
