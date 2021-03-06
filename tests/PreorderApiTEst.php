<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PreorderApiTEst extends TestCase {

    /** @test */
    public function it_can_get_preorder_lists()
    {
        $this->json('get', 'subscribe/preorders', [], $this->getAuthHeader(1930));

        $this->assertResponseOk();

        $this->dump();

        $this->seeJsonStructure(['data' => [['skus' => [['quantity']]]]]);
    }

    /** @test */
    public function it_can_get_preorder_detail()
    {
        $preorder_id = 3699;
        $this->json('get', 'subscribe/preorders/' . $preorder_id, [], $this->getAuthHeader(3878));
        $this->echoJson();

        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_get_preorder_deliver_lists()
    {
        $preorder_id = 3699;
        $this->json('get', 'subscribe/preorders/' . $preorder_id . '/deliver', [], $this->getAuthHeader());

        $this->assertResponseOk();

        $this->echoJson();

        $this->seeJsonStructure(['data' => [['skus']]]);
    }
}
