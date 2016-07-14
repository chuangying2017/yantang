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
            ['status' => \App\Services\Preorder\PreorderProtocol::ORDER_STATUS_OF_SHIPPING],
            $this->getAuthHeader());

        $this->echoJson();

        $this->seeJsonStructure(['data' => [['assign' => ['time_before']]]]);
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
}
