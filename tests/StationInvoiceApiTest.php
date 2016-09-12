<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StationInvoiceApiTest extends TestCase {

    /** @test */
    public function it_can_get_all_station_invoice_data()
    {
        $this->json('get', 'stations/invoices', [
//            'start_time' => '2016-10-01'
        ], $this->getAuthHeader(292));

        $this->echoJson();

        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_get_station_invoice_detail()
    {
        $invoice_no = '1020160910000067';
        $this->json('get', 'stations/invoices/' . $invoice_no, [
            'export' => 'all'
        ], $this->getAuthHeader(292));

        $this->dump();
    }

    /** @test */
    public function it_can_get_station_invoice_orders_detail()
    {
        $invoice_no = '1020160910000067';
        $this->json('get', 'stations/invoices/' . $invoice_no . '/orders', [
            'page' => 1
        ], $this->getAuthHeader(292));

        $this->dump();
    }


}
