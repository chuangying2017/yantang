<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StationStatementApiTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_get_stations_statements()
    {

        $this->it_can_settle_station_statement();

        $this->json('get', 'stations/statements',
            [],
            $this->getAuthHeader()
        );

        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_get_a_statement_detail()
    {

        $this->it_can_settle_station_statement();
        $this->json('get', 'stations/statements/' . 20160630011,
            [],
            $this->getAuthHeader()
        );

        $this->assertResponseOk();

//        $this->seeJsonStructure(['data' => ['skus']]);
    }


    /** @test */
    public function it_can_settle_station_statement()
    {
        app()->make(\App\Services\Statement\StationStatementService::class)->generateStatements();

        $this->seeInDatabase('statements', ['merchant_id' => 1, 'type' => \App\Services\Statement\StatementProtocol::TYPE_OF_STATION, 'settle_amount' => 800, 'year' => 2016, 'month' => 6]);
    }

    /** @test */
    public function it_can_confirm_a_station_statement()
    {
        $this->it_can_settle_station_statement();

        $this->json('put', 'stations/statements/' . 20160630011,
            [],
            $this->getAuthHeader()
        );

        $this->assertResponseOk();

        $this->seeInDatabase('statements', ['merchant_id' => 1, 'status' => \App\Services\Statement\StatementProtocol::STATEMENT_STATUS_OF_OK]);

    }


    /** @test */
    public function it_can_reject_a_station_statement()
    {
        $this->it_can_settle_station_statement();

        $this->json('put', 'stations/statements/' . 20160630011,
            ['confirm' => 0, 'memo' => '不对'],
            $this->getAuthHeader()
        );

        $this->assertResponseOk();

        $this->seeInDatabase('statements', ['merchant_id' => 1, 'status' => \App\Services\Statement\StatementProtocol::STATEMENT_STATUS_OF_ERROR, 'memo' => '不对']);

    }


}
