<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminStatementApiTest extends TestCase {

    /** @test */
    public function it_can_get_admin_store_statements_lists()
    {
        $this->json('get', 'admin/statements/store',
            [
                'year' => 2016,
                'month' => 6,
                'status' => \App\Services\Statement\StatementProtocol::STATEMENT_STATUS_OF_PENDING
            ]
            , $this->getAuthHeader());

        $this->echoJson();

        $this->seeJsonStructure(['data' => [['statement_no']]]);
    }

    /** @test */
    public function it_can_get_admin_station_statements_lists()
    {
        $this->json('get', 'admin/statements/stations',
            [
                'year' => 2016,
                'month' => 6,
                'status' => \App\Services\Statement\StatementProtocol::STATEMENT_STATUS_OF_PENDING
            ]
            , $this->getAuthHeader());

        $this->echoJson();

        $this->seeJsonStructure(['data' => [['statement_no']]]);
    }

    /** @test */
    public function it_can_get_admin_station_statements_detail()
    {
        $this->json('get', 'admin/statements/stations/20160630013', [], $this->getAuthHeader());

        $this->echoJson();
//        $this->seeJsonStructure(['data' => [['statement_no']]]);
    }

    /** @test */
    public function it_can_get_admin_store_statements_detail()
    {
        $this->json('get', 'admin/statements/store/20160630021', [], $this->getAuthHeader());

        $this->echoJson();
//        $this->seeJsonStructure(['data' => [['statement_no']]]);
    }
}
