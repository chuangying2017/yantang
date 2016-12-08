<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminStationInvoiceTest extends TestCase {

    /** @test */
    public function it_can_get_all_station_admin_invoice_data()
    {
        $this->json('get', 'admin/invoices/stations', [
//            'start_time' => '2016-10-01'
        ], $this->getAuthHeader());

        $this->echoJson();

        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_get_station_admin_invoice_detail()
    {
        $invoice_no = '1020160925000000';
        $this->json('get', 'admin/invoices/stations/' . $invoice_no, [
            'export' => 'summary'
        ], $this->getAuthHeader());

        $this->dump();

        $this->echoJson();

        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_get_station_admin_invoice_detail_orders()
    {
        $invoice_no = '1020160910000067';
        $this->json('get', 'admin/invoices/stations/' . $invoice_no . '/orders', [

        ], $this->getAuthHeader());

        $this->echoJson();
        $this->assertResponseOk();
    }


    /** @test */
    public function it_can_get_weazm_bounce()
    {
        $this->json('get', 'admin/invoices/bonus', [
            'date' => '2016-10'
        ], $this->getAuthHeader(1));

        $this->echoJson();
    }
}

