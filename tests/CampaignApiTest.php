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
        $sku_id = 1;
        $data = [
            'name' => '优惠购',
            'cover_image' => 'adada',
            'desc' => '描述',
            'detail' => '详细描述',
            'start_time' => '2016-06-01',
            'end_time' => '2016-12-01',
            'active' => 1,
            'product_sku' => $sku_id
        ];

        $this->json('post', 'admin/campaigns',
            $data,
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]);
        $this->dumpResponse();

        $this->assertResponseStatus(201);

        $result = $this->getResponseData();

        $this->seeInDatabase('promotion_skus', ['promotion_id' => $result['data']['id'], 'product_sku_id' => $sku_id]);
        $this->seeInDatabase('promotion_info', ['promotion_id' => $result['data']['id']]);
    }

    /** @test */
    public function it_can_get_a_campaign_lists()
    {
        $user_id = 1;

        $this->json('get', 'campaigns/campaigns', [],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->assertResponseOk();

        $this->dumpResponse();

    }


    /** @test */
    public function it_can_get_a_campaign()
    {
        $user_id = 1;
        $campaign_id = 5;
        $this->json('get', 'campaigns/campaigns/' . $campaign_id, [],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->dumpResponse();

        $this->assertResponseOk();

        $this->seeJsonStructure(['data' => ['id', 'detail', 'product_sku']]);
    }


}
