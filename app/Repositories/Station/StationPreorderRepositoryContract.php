<?php namespace App\Repositories\Station;

use App\Repositories\Preorder\PreorderRepositoryContract;
use App\Services\Preorder\PreorderProtocol;

interface StationPreorderRepositoryContract extends PreorderRepositoryContract{

    public function getPreordersOfStation($station_id = null, $staff_id = null, $status = null, $charge_status = null, $start_time = null, $end_time = null, $order_by = 'created_at', $sort = 'desc', $per_page = PreorderProtocol::PREORDER_PER_PAGE);

    public function getPreordersOfStationNotConfirm($station_id);

    public function getDayPreordersOfStation($station_id, $day = null, $daytime = null);

    public function getDayPreordersOfStaff($staff_id = null, $day = null, $daytime = null, $per_page = null);

    public function updatePreorderPriority($order_id, $staff_id, $preorder_priority);

    public function updatePreorderByStation($order_id, $start_time = null, $end_time = null, $product_skus = null, $station_id = null);

}
