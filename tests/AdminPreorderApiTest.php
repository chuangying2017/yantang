<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminPreorderApiTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_get_all_preorders()
    {
        $this->json('get', 'admin/subscribe/orders',
            [
                'status' => \App\Services\Preorder\PreorderProtocol::ASSIGN_STATUS_OF_OVERTIME
            ],
            $this->getAuthHeader());

        $this->dump();
    }

    /** @test */
    public function it_can_export_all_preorders()
    {
        $this->json('get', 'admin/subscribe/orders',
            [
                'status' => \App\Services\Preorder\PreorderProtocol::ORDER_STATUS_OF_ASSIGNING,
                'export' => 'all'
            ],
            $this->getAuthHeader());
        $this->dump();
    }

    /** @test */
    public function it_can_show_a_preorder_detail()
    {
        $this->json('get', 'admin/subscribe/orders/' . 15,
            [],
            $this->getAuthHeader());

        $this->echoJson();

    }

    /** @test */
    public function it_can_assign_new_station_for_preorder()
    {
        $preorder_id = 15;
        $station_id = 2;

        $this->json('put', 'admin/subscribe/orders/' . $preorder_id,
            [
                'station' => $station_id
            ],
            $this->getAuthHeader());

        $this->echoJson();

        $this->assertResponseStatus(200);

        $this->seeInDatabase('preorder_assign', ['preorder_id' => $preorder_id, 'station_id' => $station_id, 'status' => \App\Services\Preorder\PreorderProtocol::ASSIGN_STATUS_OF_UNTREATED]);
    }

    /** @test */
    public function it_can_get_all_not_handle_on_time_preorders()
    {
        $this->json('get', 'admin/subscribe/preorders/reject',
            [

            ],
            $this->getAuthHeader());

        $this->echoJson();

        $this->assertResponseStatus(200);
    }
}
