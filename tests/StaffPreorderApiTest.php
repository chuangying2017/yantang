<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StaffPreorderApiTest extends TestCase {

    /** @test */
    public function it_can_show_staff_preorder_lists()
    {
        $this->json('get', 'stations/staffs/preorders', [], $this->getAuthHeader(2));

        $this->assertResponseOk();
    }


    /** @test */
    public function it_can_show_staff_preorder_detail()
    {
        $order_id = 2;

        $this->json('get', 'stations/staffs/preorders/' . $order_id, [], $this->getAuthHeader(2));

        $this->assertResponseOk();
    }

}
