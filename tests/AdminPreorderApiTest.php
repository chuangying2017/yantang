<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminPreorderApiTest extends TestCase {

    /** @test */
    public function it_can_get_all_preorders()
    {
        $this->json('get', 'admin/subscribe/orders',
            [],
            $this->getAuthHeader());

        $this->seeJsonStructure(['data' => [['assign' => ['time_before']]]]);
    }

    /** @test */
    public function it_can_assign_new_station_for_preorder()
    {
//        $this->json('put', ''])

    }
}
