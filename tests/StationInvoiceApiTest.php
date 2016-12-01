<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StationInvoiceApiTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_get_all_station_invoice_data()
    {
        $this->json('get', 'stations/invoices', [
            'start_time' => '2016-9-01'
        ], $this->getAuthHeader(292));

        $this->dump();

        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_get_station_invoice_detail()
    {
        $invoice_no = '1020160910000067';
        $this->json('get', 'stations/invoices/' . $invoice_no, [
//            'export' => 'all'
        ], $this->getAuthHeader(292));

        $this->dump();
    }

    /** @test */
    public function it_can_get_station_invoice_orders_detail()
    {
        $invoice_no = '1020160910000012';
        $this->json('get', 'stations/invoices/' . $invoice_no . '/orders', [
            'page' => 1,
            'staff' => 37
        ], $this->getAuthHeader(84));

        $this->dump();
    }

    /** @test */
    public function it_can_confirm_or_reject_a_invoice()
    {
        $invoice_no = '1020160910000067';
        $status = \App\Repositories\Invoice\InvoiceProtocol::INVOICE_STATUS_OF_REJECT;
        $this->json('put', 'stations/invoices/' . $invoice_no, [
            'action' => $status,
            'memo' => ''
        ], $this->getAuthHeader(292));

        $this->assertResponseOk();

        $this->seeInDatabase('invoices', ['invoice_no' => $invoice_no, 'status' => $status]);
    }


}
