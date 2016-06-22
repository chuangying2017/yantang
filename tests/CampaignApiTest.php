<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CampaignApiTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_create_campaign()
    {
        $user_id = 1;

        $data = [
            'name' => '优惠购',
            'cover_image' => 'adada',
            'desc' => '描述',
            'detail' => '详细描述',
            'start_time' => '2016-06-01',
            'end_time' => '2016-12-01',
            'active' => 1
        ];

        $this->json('post', 'admin/campaigns',
            $data,
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]);
        $this->dumpResponse();

        $this->assertResponseStatus(201);
    }
}
