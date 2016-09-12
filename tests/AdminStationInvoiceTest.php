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
        $invoice_no = '1020160910000000';
        $this->json('get', 'admin/invoices/stations/' . $invoice_no, [

        ], $this->getAuthHeader());

        $this->dump();
    }
}
