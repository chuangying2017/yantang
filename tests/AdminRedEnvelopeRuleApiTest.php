<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminRedEnvelopeRuleApiTest extends TestCase {

//    use DatabaseMigrations;
    use DatabaseTransactions;


    /** @test */
    public function it_can_get_red_rules_list()
    {
        $this->json('get', 'admin/promotions/red-envelopes', [], $this->getAuthHeader());

        $this->echoJson();

        $this->assertResponseStatus(200);
    }

    /** @test */
    public function it_can_show_a_red_rules_list()
    {
        $rule_id = '1';
        $this->json('get', 'admin/promotions/red-envelopes/' . $rule_id, [], $this->getAuthHeader());

        $this->dump();
    }

    /** @test */
    public function it_can_update_a_red_envelope()
    {
        $rule_id = '1';
        $this->json('put', 'admin/promotions/red-envelopes/' . $rule_id, [
            'name' => '10月红包',
            'desc' => '10月红包',
            'type' => \App\Repositories\RedEnvelope\RedEnvelopeProtocol::TYPE_OF_SUBSCRIBE_ORDER,
            'start_time' => '2016-09-01',
            'end_time' => '2016-11-01',
            'coupons' => [13],
            'quantity' => 10,
            'effect_days' => 3,
            'content' => '200元红包'
        ], $this->getAuthHeader());
        $this->dump();
    }

    /** @test */
    public function it_can_active_a_red_rule()
    {
        $rule_id = '1';
        $this->json('put', 'admin/promotions/red-envelopes/' . $rule_id . '/unactive', [
        ], $this->getAuthHeader());
        $this->dump();
    }

    /** @test */
    public function it_can_create_a_red_envelope_rule()
    {
//        echo json_encode($this->getRuleData());

        $this->json('post', 'admin/promotions/red-envelopes', $this->getRuleData(), $this->getAuthHeader());

        $this->echoJson();

        $this->assertResponseStatus(201);
    }


    protected function getRuleData()
    {
        $data = [
            'name' => '10月红包',
            'desc' => '10月红包',
            'type' => \App\Repositories\RedEnvelope\RedEnvelopeProtocol::TYPE_OF_SUBSCRIBE_ORDER,
            'start_time' => '2016-09-01',
            'end_time' => '2016-11-01',
            'coupons' => [13],
            'quantity' => 10,
            'effect_days' => 3,
            'content' => '200元红包'
        ];

        return $data;
    }
}
