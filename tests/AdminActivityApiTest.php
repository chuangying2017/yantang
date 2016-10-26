<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminActivityApiTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_get_activity_lists()
    {
        $this->it_can_create_a_activity();
        $this->it_can_create_a_activity();
        $this->it_can_create_a_activity();
        $this->it_can_create_a_activity();
        $this->it_can_create_a_activity();

        $this->json('get', 'admin/promotions/activities', [], $this->getAuthHeader(1));

        $this->echoJson();

        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_create_a_activity()
    {
        $data = [
            'name' => '活动1',
            'desc' => '规则',
            'priority' => random_int(1, 100),
            'cover_image' => 'cover_image',
            'background_color' => '#231233',
            'start_time' => '2016-10-01 00:00:00',
            'end_time' => '2016-12-01 00:00:00',
            'coupons' => [17, 18],
        ];

//        echo json_encode($data) . "\n";

        $this->json('post', 'admin/promotions/activities', $data, $this->getAuthHeader(1));

//        $this->echoJson();

        $this->assertResponseStatus(201);

        return $this->getResponseData('data.activity_no');
    }

    /** @test */
    public function it_can_get_a_activity_detail()
    {
        $activity_no = $this->it_can_create_a_activity();

        $this->json('get', 'admin/promotions/activities/' . $activity_no, [], $this->getAuthHeader());

        $this->echoJson();

        $this->seeJsonStructure(['data' => ['coupons']]);

        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_set_activity_as_unactive()
    {
        $activity_no = $this->it_can_create_a_activity();

        $this->json('put', 'admin/promotions/activities/' . $activity_no . '/unactive', [
        ], $this->getAuthHeader());

        $this->seeJsonStructure(['data' => ['status']]);

        $this->echoJson();
    }

    /** @test */
    public function it_can_set_activity_as_active()
    {
        $activity_no = $this->it_can_create_a_activity();

        $this->json('put', 'admin/promotions/activities/' . $activity_no . '/active', [
        ], $this->getAuthHeader());

        $this->seeJsonStructure(['data' => ['status']]);

        $this->echoJson();
    }

    /** @test */
    public function it_can_show_act_frontend()
    {
        $this->it_can_create_a_activity();

        $this->json('get', 'promotions/activities', [], $this->getAuthHeader());

        $this->echoJson();

        $this->assertResponseStatus(200);
    }

    /** @test */
    public function it_can_show_act_detail_frontend()
    {
        $id = $this->it_can_create_a_activity();

        $this->json('get', 'promotions/activities/' . $id, [], $this->getAuthHeader());

        $this->echoJson();

        $this->assertResponseStatus(200);
    }

}
