<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StaffPreorderApiTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_show_staff_preorder_lists()
    {
        $this->json('get', 'staffs/preorders',
            [
                'keyword' => '108160828954221082143'
            ],
            $this->getAuthHeader(284));

        $this->echoJson();

        $this->dumpResponse();

        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_get_staff_preorder_lists()
    {
        $this->json('get', 'staffs/preorders/daily',
            [
                'date' => '2016-07-19'
            ],
            $this->getAuthHeader(2));

        $this->echoJson();

        $this->seeJsonStructure(['meta' => ['summary']]);

        $this->dumpResponse();

        $this->assertResponseOk();
    }


    /** @test */
    public function it_can_show_staff_preorder_detail()
    {
        $order_id = 15;

        $this->json('get', 'staffs/preorders/' . $order_id, [], $this->getAuthHeader(2));

        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_pause_a_preorder()
    {
        $order_id = 15;

        $pause_time = '2016-07-15';
        $data = [
            'pause_time' => $pause_time
        ];

        $this->json('put', 'staffs/preorders/' . $order_id . '/pause',
            $data,
            $this->getAuthHeader(2)
        );

        $this->assertResponseOk();

        $this->seeInDatabase('preorders', ['id' => $order_id, 'pause_time' => $pause_time]);
    }

    /** @test */
    public function it_can_restart_a_preorder()
    {
        $this->it_can_pause_a_preorder();
        $order_id = 15;

        $restart_time = '2016-07-14';
        $data = [
            'restart_time' => $restart_time
        ];

        $this->json('put', 'staffs/preorders/' . $order_id . '/restart',
            $data,
            $this->getAuthHeader(2)
        );

        $this->assertResponseOk();

        $this->seeInDatabase('preorders', ['id' => $order_id, 'restart_time' => $restart_time]);
    }


    /** @test */
    public function it_can_assign_all_preorder_from_the_staff_to_a_new_staff()
    {
        $staff = $this->it_can_create_a_staff();

        $old_staff_id = 1;

        $this->json('put', 'stations/staffs/' . $old_staff_id . '/preorders', ['staff' => $staff['id']], $this->getAuthHeader());

        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_create_a_staff()
    {
        $user_id = 1;
        $this->json('post', 'stations/staffs',
            [
                'name' => '服务部' . mt_rand(),
                'phone' => '服务部描述' . mt_rand()
            ],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->assertResponseStatus(201);

        return $this->getResponseData('data');
    }

}
