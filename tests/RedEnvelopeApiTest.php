<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RedEnvelopeApiTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_show_a_red_envelope()
    {
        $this->it_can_get_a_ticket_from_red_envelope();

        $record_id = 1;

        $this->json('get', 'promotions/red-envelopes/' . $record_id, [], $this->getAuthHeader());

        $this->dump();

        $this->echoJson();

        $this->assertResponseOk();

        $this->seeJsonStructure(['data' => ['current_receiver', 'rule', 'receivers' => []]]);
    }

    /** @test */
    public function it_can_get_a_ticket_from_red_envelope()
    {
        $record_id = 1;

        $this->json('post', 'promotions/red-envelopes', [
            'record' => $record_id
        ], $this->getAuthHeader());

        $this->assertResponseStatus(201);

        $this->seeInDatabase('tickets', [
            'user_id' => 1,
            'source_type' => \App\Services\Promotion\PromotionProtocol::TICKET_RESOURCE_OF_RED_ENVELOPE,
            'source_id' => $record_id
        ]);

        $this->echoJson();

    }
}
