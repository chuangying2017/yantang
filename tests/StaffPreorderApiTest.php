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
                'status' => \App\Services\Preorder\PreorderProtocol::ORDER_STATUS_OF_SHIPPING
            ],
            $this->getAuthHeader(2));

        $this->echoJson();

        $this->dumpResponse();

        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_get_staff_preorder_lists()
    {
        $this->json('get', 'staffs/preorders/daily',
            [
                'date' => '2016-07-11'
            ],
            $this->getAuthHeader(2));

        $this->echoJson();

        $this->seeJsonStructure(['meta' => ['summary']]);

        $this->assertResponseOk();
    }


    /** @test */
    public function it_can_show_staff_preorder_detail()
    {
        $order_id = 15;

        $this->json('get', 'staffs/preorders/' . $order_id, [], $this->getAuthHeader(2));

        $this->dumpResponse();

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

        $this->echoJson();

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

}
