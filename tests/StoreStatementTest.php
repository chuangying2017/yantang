<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StoreStatementTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_get_store_statements()
    {
        $this->it_can_settle_store_statement();

        $this->json('get', 'store/statements',
            [],
            $this->getAuthHeader()
        );

        $this->dump();

        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_get_a_statement_detail()
    {
//        $this->it_can_settle_store_statement();
        $this->json('get', 'store/statements/' . 20160630021,
            [],
            $this->getAuthHeader()
        );

        $this->assertResponseOk();

        $this->seeJsonStructure(['data' => ['skus', 'settle_amount', 'service_amount']]);
    }


    /** @test */
    public function it_can_settle_store_statement()
    {
        app()->make(\App\Services\Statement\StoreStatementService::class)->generateStatements();

        $settle_amount = 0;
        $this->seeInDatabase('statements', ['merchant_id' => 1, 'type' => \App\Services\Statement\StatementProtocol::TYPE_OF_STORE, 'settle_amount' => $settle_amount, 'year' => 2016, 'month' => 6]);
        $this->seeInDatabase('statement_products', ['statement_no' => '20160630011', 'product_sku_id' => 2, 'quantity' => 2]);
        $this->seeInDatabase('statement_products', ['statement_no' => '20160630011', 'product_sku_id' => 3, 'quantity' => 6]);
    }

    /** @test */
    public function it_can_confirm_a_store_statement()
    {
        $this->it_can_settle_store_statement();

        $this->json('put', 'store/statements/' . 20160630011,
            [],
            $this->getAuthHeader()
        );

        $this->assertResponseOk();

        $this->seeInDatabase('statements', ['merchant_id' => 1, 'status' => \App\Services\Statement\StatementProtocol::STATEMENT_STATUS_OF_OK]);
    }


    /** @test */
    public function it_can_reject_a_store_statement()
    {
        $this->it_can_settle_store_statement();

        $this->json('put', 'store/statements/' . 20160630011,
            ['confirm' => 0, 'memo' => '不对'],
            $this->getAuthHeader()
        );

        $this->assertResponseOk();

        $this->seeInDatabase('statements', ['merchant_id' => 1, 'status' => \App\Services\Statement\StatementProtocol::STATEMENT_STATUS_OF_ERROR, 'memo' => '不对']);

    }


}
