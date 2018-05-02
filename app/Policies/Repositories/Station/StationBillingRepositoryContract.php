<?php namespace App\Repositories\Station;

use App\Services\Billing\BillingProtocol;

interface StationBillingRepositoryContract {

    public function getBillingsByStationPaginated($station_id, $status = null, $start_time = null, $end_time = null, $per_page = BillingProtocol::BILLING_PER_PAGE);

    public function getBillingsByStaffPaginated($staff_id, $status = null, $start_time = null, $end_time = null, $per_page = BillingProtocol::BILLING_PER_PAGE);



}
