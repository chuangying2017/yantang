<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminRedEnvelopeRuleApiTest extends TestCase {

    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @test */
    public function it_can_create_a_red_envelope_rule()
    {

        $this->json('post', 'admin/promotions/red-envelopes', $this->getRuleData(), $this->getAuthHeader());

        $this->dump();

        $this->assertResponseStatus(201);
    }


    protected function getRuleData()
    {
        $data = [
            'name' => '红包',
            'desc' => 'desc',
            'type' => \App\Repositories\RedEnvelope\RedEnvelopeProtocol::TYPE_OF_ORDER,
            'start_time' => '2016-09-01',
            'end_time' => '2016-11-01',
            'coupons' => [1, 1, 1],
            'quantity' => 10,
            'effect_days' => 10,
            'content' => '50元红包'
        ];

        return $data;
    }
}
