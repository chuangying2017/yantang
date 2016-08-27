<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderRefundApiTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_apply_order_refund()
    {

        $data = [
            'memo' => 'test',
            'order_skus' => [
                ['id' => 145, 'quantity' => 15],
                ['id' => 146, 'quantity' => 90],
            ]
        ];

        $order_no = '102160827933554722998';

        $this->json('delete', 'subscribe/orders/' . $order_no, $data, $this->getAuthHeader());

        $this->dump();
        $this->assertResponseOk();

    }
}
