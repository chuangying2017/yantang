<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StationStatementTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_settle_station_statement()
    {
        app()->make(\App\Services\Statement\StationStatementService::class)->generateStatements();

        $settle_amount = 82400;
        $this->seeInDatabase('statements', ['merchant_id' => 1, 'type' => \App\Services\Statement\StatementProtocol::TYPE_OF_STATION, 'settle_amount' => $settle_amount, 'year' => 2016, 'month' => 6]);
//        $this->seeInDatabase('statement_products', ['statement_no' => '20160630011', 'product_sku_id' => 2, 'quantity' => 2]);
//        $this->seeInDatabase('statement_products', ['statement_no' => '20160630011', 'product_sku_id' => 3, 'quantity' => 6]);

    }
}
