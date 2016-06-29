<?php namespace App\Repositories\Station;

use App\Services\Preorder\PreorderProtocol;
use Carbon\Carbon;

interface StationPreorderRepositoryContract {

    public function getPreordersOfStation($station_id, $staff_id = null, $status = null, $charge_status = null, $start_time = null, $end_time = null, $order_by = 'created_at', $sort = 'desc', $per_page = PreorderProtocol::PREORDER_PER_PAGE);

    public function getDayPreordersOfStation($station_id, $staff_id = null, $day = null, $daytime = null, $per_page = null);

    public function updatePreorderPriority($order_id, $staff_id, $preorder_priority);

}
