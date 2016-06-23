<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderTicketApiTest extends TestCase
{
    /** @test */
    public function it_can_get_order_ticket_lists()
    {
        $user_id = 1;
        $this->json('GET', 'campaigns/order-tickets', [],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->assertResponseOk();

        $this->dump();
    }

    /** @test */
    public function it_can_get_order_ticket_detail()
    {
        $ticket_no = '10716062364115856865812';

        $user_id = 1;

        $this->json('GET', 'campaigns/order-tickets/' . $ticket_no,
            [],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->dumpResponse();
        $this->assertResponseOk();

    }
}
