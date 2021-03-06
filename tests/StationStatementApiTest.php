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
        $statement_no = $this->getStatementNo();
        $this->json('get', 'stations/statements/' . $statement_no,
            [],
            $this->getAuthHeader()
        );

        $this->assertResponseOk();

        $this->dumpResponse();

        $this->seeJsonStructure(['data' => ['skus', 'settle_amount', 'service_amount']]);
    }


    /** @test */
    public function it_can_settle_station_statement()
    {
        app()->make(\App\Services\Statement\StationStatementService::class)->generateStatements();
        $merchant_id = 1;
        $statement_no = $this->getStatementNo($merchant_id);
        $this->seeInDatabase('statements', ['merchant_id' => $merchant_id, 'type' => \App\Services\Statement\StatementProtocol::TYPE_OF_STATION, 'settle_amount' => 154280, 'year' => 2016, 'month' => \Carbon\Carbon::today()->month]);
        $this->seeInDatabase('statement_products', ['statement_no' => $statement_no, 'product_sku_id' => 2, 'quantity' => 4]);
        $this->seeInDatabase('statement_products', ['statement_no' => $statement_no, 'product_sku_id' => 3, 'quantity' => 6]);
    }

    /** @test */
    public function it_can_confirm_a_station_statement()
    {
        $this->it_can_settle_station_statement();

        $this->json('put', 'stations/statements/' . $this->getStatementNo(),
            [],
            $this->getAuthHeader()
        );

        $this->assertResponseOk();

        $this->seeInDatabase('statements', ['statement_no' => $this->getStatementNo(), 'status' => \App\Services\Statement\StatementProtocol::STATEMENT_STATUS_OF_OK]);
    }


    /** @test */
    public function it_can_reject_a_station_statement()
    {
        $this->it_can_settle_station_statement();

        $this->json('put', 'stations/statements/' . $this->getStatementNo(),
            ['confirm' => 0, 'memo' => '不对'],
            $this->getAuthHeader()
        );

        $this->assertResponseOk();

        $this->seeInDatabase('statements', ['statement_no' => $this->getStatementNo(), 'status' => \App\Services\Statement\StatementProtocol::STATEMENT_STATUS_OF_ERROR, 'memo' => '不对']);

    }

    /**
     * @return string
     */
    protected function getStatementNo($merchant_id = 1)
    {
        $statement_no = date('Ymd') . '01' . $merchant_id;
        return $statement_no;
    }

}
