<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderTicketApiTest extends \CampaignOrderTest {

    use DatabaseTransactions;

    /** @test */
    public function it_can_get_order_ticket_lists()
    {
        $user_id = 1;
        $this->json('GET', 'campaigns/tickets', [],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_get_order_ticket_detail()
    {
        $order = $this->it_can_paid_a_campaign_order();

        $ticket = \App\Models\OrderTicket::where('order_id', $order['id'])->first();

        $user_id = 1;

        $this->json('GET', 'campaigns/tickets/' . $ticket['ticket_no'],
            [],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->dumpResponse();
        $this->assertResponseOk();

    }
}
